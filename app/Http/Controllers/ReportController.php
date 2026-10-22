<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Mengarahkan tampilan dashboard berdasarkan role user yang login.
     */
    public function index(Request $request): View
    {
        return match ($request->user()->role) {
            'admin' => $this->adminDashboard(),
            'petugas' => $this->petugasDashboard($request),
            default => $this->wargaDashboard($request),
        };
    }

    private function wargaDashboard(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $reports = Report::with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.warga', compact('categories', 'reports'));
    }

    private function adminDashboard(): View
    {
        $counts = Report::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $kpi = [
            'total' => (int) $counts->sum(),
            'pending' => (int) ($counts[Report::STATUS_PENDING] ?? 0),
            'processing' => (int) (($counts[Report::STATUS_VALIDATED] ?? 0) + ($counts[Report::STATUS_IN_PROGRESS] ?? 0)),
            'completed' => (int) ($counts[Report::STATUS_COMPLETED] ?? 0),
            'rejected' => (int) ($counts[Report::STATUS_REJECTED] ?? 0),
            'avg_rating' => round((float) Report::whereNotNull('rating')->avg('rating'), 1),
        ];

        $pendingReports = Report::with(['user', 'category'])
            ->where('status', Report::STATUS_PENDING)
            ->oldest()
            ->paginate(10);

        $coveredCategoryIds = User::where('role', User::ROLE_PETUGAS)
            ->whereNotNull('category_id')
            ->where('is_active', true)
            ->pluck('category_id')
            ->unique()
            ->values()
            ->all();

        $categoryRecap = Category::withCount([
                'reports',
                'reports as completed_count' => fn ($q) => $q->where('status', Report::STATUS_COMPLETED),
                'petugas',
            ])
            ->withAvg('reports', 'rating')
            ->orderBy('name')
            ->get();

        $latestReviews = Report::with(['user:id,name', 'category'])
            ->whereNotNull('rating')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'kpi',
            'pendingReports',
            'coveredCategoryIds',
            'categoryRecap',
            'latestReviews'
        ));
    }

    private function petugasDashboard(Request $request): View
    {
        $user = $request->user();
        $category = $user->category;

        if (! $category) {
            return view('dashboard.petugas', [
                'category' => null,
                'queueReports' => collect(),
                'myReports' => collect(),
                'teamReports' => collect(),
                'completedReports' => collect(),
                'completedToday' => 0,
            ]);
        }

        $queueReports = Report::with('user:id,name')
            ->where('category_id', $category->id)
            ->where('status', Report::STATUS_VALIDATED)
            ->whereNull('petugas_id')
            ->oldest()
            ->get();

        $myReports = Report::with('user:id,name')
            ->where('category_id', $category->id)
            ->where('status', Report::STATUS_IN_PROGRESS)
            ->where('petugas_id', $user->id)
            ->oldest()
            ->get();

        $teamReports = Report::with(['user:id,name', 'petugas:id,name'])
            ->where('category_id', $category->id)
            ->where('status', Report::STATUS_IN_PROGRESS)
            ->where(fn ($q) => $q->whereNull('petugas_id')->orWhere('petugas_id', '!=', $user->id))
            ->oldest()
            ->get();

        $completedReports = Report::with(['user:id,name', 'petugas:id,name'])
            ->where('category_id', $category->id)
            ->where('status', Report::STATUS_COMPLETED)
            ->latest('updated_at')
            ->paginate(9);

        $completedToday = Report::where('category_id', $category->id)
            ->where('status', Report::STATUS_COMPLETED)
            ->whereDate('updated_at', today())
            ->count();

        return view('dashboard.petugas', compact(
            'category',
            'queueReports',
            'myReports',
            'teamReports',
            'completedReports',
            'completedToday'
        ));
    }

    /**
     * Warga mengirim laporan baru. Kategori dikirim & divalidasi sebagai ID.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:3000'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photo_before' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['photo_before'] = $request->file('photo_before')->store('reports/before', 'public');
        $data['user_id'] = $request->user()->id;
        $data['status'] = Report::STATUS_PENDING;

        $report = Report::create($data);

        app(AuditLogger::class)->log(
            'report.created',
            'report',
            $report->id,
            'Laporan dibuat oleh warga.',
            null,
            [
                'title' => $report->title,
                'category_id' => $report->category_id,
                'location' => $report->location,
            ]
        );

        return redirect()->route('dashboard')
            ->with('success', 'Laporan berhasil dikirim dan menunggu verifikasi admin.');
    }

    /**
     * Admin memvalidasi atau menolak laporan.
     * Laporan yang disetujui (VALIDATED) masuk antrean petugas yang memiliki
     * category_id sama, menunggu diambil oleh salah satu petugas.
     */
    public function verify(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:validate,reject'],
            'rejection_note' => ['required_if:action,reject', 'nullable', 'string', 'max:1000'],
        ], [
            'rejection_note.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($report->status !== Report::STATUS_PENDING) {
            return back()->with('error', 'Hanya laporan berstatus PENDING yang dapat diverifikasi.');
        }

        if ($data['action'] === 'validate') {
            $hasPetugas = User::where('role', User::ROLE_PETUGAS)
                ->where('category_id', $report->category_id)
                ->where('is_active', true)
                ->exists();

            if (! $hasPetugas) {
                return back()->with('error', 'Belum ada petugas untuk kategori "' . $report->category->name
                    . '". Tambahkan petugas terlebih dahulu sebelum menyetujui laporan ini.');
            }

            $oldStatus = $report->status;
            $report->update([
                'status' => Report::STATUS_VALIDATED,
                'rejection_note' => null,
            ]);

            app(AuditLogger::class)->log(
                'report.validated',
                'report',
                $report->id,
                'Laporan disetujui dan masuk antrean petugas.',
                ['status' => $oldStatus],
                ['status' => $report->status]
            );

            return back()->with('success', 'Laporan #' . $report->id . ' disetujui dan masuk antrean petugas '
                . $report->category->name . '.');
        }

        $oldStatus = $report->status;
        $report->update([
            'status' => Report::STATUS_REJECTED,
            'rejection_note' => $data['rejection_note'],
        ]);

        app(AuditLogger::class)->log(
            'report.rejected',
            'report',
            $report->id,
            'Laporan ditolak oleh admin.',
            ['status' => $oldStatus],
            ['status' => $report->status, 'rejection_note' => $data['rejection_note']]
        );

        return back()->with('success', 'Laporan #' . $report->id . ' ditolak (REJECTED).');
    }

    /**
     * Petugas mengambil (mengunci) laporan VALIDATED pada kategorinya.
     * Memakai update bersyarat sehingga hanya SATU petugas yang berhasil,
     * walaupun dua petugas menekan tombol hampir bersamaan.
     */
    public function startWork(Request $request, Report $report): RedirectResponse
    {
        $petugas = $request->user();
        $this->authorizeCategory($petugas, $report);

        $claimed = Report::whereKey($report->id)
            ->where('status', Report::STATUS_VALIDATED)
            ->whereNull('petugas_id')
            ->update([
                'status' => Report::STATUS_IN_PROGRESS,
                'petugas_id' => $petugas->id,
            ]);

        if ($claimed === 0) {
            return back()->with('error', 'Laporan ini sudah diambil petugas lain atau tidak tersedia.');
        }

        app(AuditLogger::class)->log(
            'report.claimed',
            'report',
            $report->id,
            'Laporan diambil oleh petugas untuk dikerjakan.',
            null,
            ['status' => Report::STATUS_IN_PROGRESS, 'petugas_id' => $petugas->id]
        );

        return back()->with('success', 'Laporan #' . $report->id . ' kini Anda kerjakan.');
    }

    /**
     * Petugas melepas laporan miliknya kembali ke antrean (misalnya berhalangan).
     */
    public function releaseWork(Request $request, Report $report): RedirectResponse
    {
        $petugas = $request->user();
        $this->authorizeCategory($petugas, $report);

        $oldStatus = $report->status;
        $oldPetugas = $report->petugas_id;
        $released = Report::whereKey($report->id)
            ->where('status', Report::STATUS_IN_PROGRESS)
            ->where('petugas_id', $petugas->id)
            ->update([
                'status' => Report::STATUS_VALIDATED,
                'petugas_id' => null,
            ]);

        if ($released === 0) {
            return back()->with('error', 'Hanya petugas yang mengambil laporan yang dapat melepasnya.');
        }

        $report->refresh();
        app(AuditLogger::class)->log(
            'report.released',
            'report',
            $report->id,
            'Laporan dikembalikan ke antrean oleh petugas.',
            ['status' => $oldStatus, 'petugas_id' => $oldPetugas],
            ['status' => $report->status, 'petugas_id' => $report->petugas_id]
        );

        return back()->with('success', 'Laporan #' . $report->id . ' dikembalikan ke antrean.');
    }

    /**
     * Petugas yang mengambil laporan menyelesaikannya dengan bukti foto.
     */
    public function completeWork(Request $request, Report $report): RedirectResponse
    {
        $petugas = $request->user();
        $this->authorizeCategory($petugas, $report);

        $data = $request->validate([
            'photo_after' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'executor_name' => ['required', 'string', 'max:100'],
            'executor_note' => ['required', 'string', 'max:2000'],
        ]);

        if ($report->status !== Report::STATUS_IN_PROGRESS) {
            return back()->with('error', 'Hanya laporan berstatus IN_PROGRESS yang dapat diselesaikan.');
        }

        if ($report->petugas_id !== $petugas->id) {
            return back()->with('error', 'Laporan ini sedang dikerjakan petugas lain.');
        }

        $report->update([
            'photo_after' => $request->file('photo_after')->store('reports/after', 'public'),
            'executor_name' => $data['executor_name'],
            'executor_note' => $data['executor_note'],
            'status' => Report::STATUS_COMPLETED,
        ]);

        app(AuditLogger::class)->log(
            'report.completed',
            'report',
            $report->id,
            'Laporan selesai dikerjakan petugas.',
            null,
            [
                'status' => Report::STATUS_COMPLETED,
                'executor_name' => $data['executor_name'],
                'executor_note' => $data['executor_note'],
                'petugas_id' => $petugas->id,
            ]
        );

        return back()->with('success', 'Laporan #' . $report->id . ' ditandai selesai.');
    }

    private function authorizeCategory(User $petugas, Report $report): void
    {
        abort_unless(
            $petugas->category_id !== null && $report->category_id === $petugas->category_id,
            403,
            'Laporan ini bukan bagian dari kategori pekerjaan Anda.'
        );
    }

    /**
     * Warga memberi rating & review pada laporan COMPLETED miliknya.
     */
    public function rate(Request $request, Report $report): RedirectResponse
    {
        abort_unless($report->user_id === $request->user()->id, 403, 'Ini bukan laporan Anda.');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($report->status !== Report::STATUS_COMPLETED) {
            return back()->with('error', 'Rating hanya dapat diberikan pada laporan yang sudah selesai.');
        }

        if ($report->rating !== null) {
            return back()->with('error', 'Anda sudah memberi rating untuk laporan ini.');
        }

        $report->update([
            'rating' => $data['rating'],
            'review' => $data['review'] ?? null,
        ]);

        app(AuditLogger::class)->log(
            'report.rated',
            'report',
            $report->id,
            'Warga memberi penilaian pada laporan.',
            null,
            ['rating' => $data['rating'], 'review' => $data['review'] ?? null]
        );

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
