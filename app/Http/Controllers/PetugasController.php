<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PetugasController extends Controller
{
    public function index(): View
    {
        $petugasList = User::with('category')
            ->where('role', User::ROLE_PETUGAS)
            ->withCount([
                'handledReports as active_count' => fn ($q) => $q->where('status', Report::STATUS_IN_PROGRESS),
                'handledReports as done_count' => fn ($q) => $q->where('status', Report::STATUS_COMPLETED),
            ])
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.petugas.index', compact('petugasList'));
    }

    public function create(): View
    {
        return view('admin.petugas.form', [
            'petugas' => null,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $petugas = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'category_id' => $data['category_id'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_PETUGAS,
            'is_active' => true,
        ]);

        app(AuditLogger::class)->log(
            'petugas.created',
            'petugas',
            $petugas->id,
            'Petugas baru dibuat.',
            null,
            ['name' => $petugas->name, 'email' => $petugas->email, 'category_id' => $petugas->category_id]
        );

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Akun petugas "' . $data['name'] . '" berhasil dibuat.');
    }

    public function edit(User $petugas): View
    {
        $this->ensurePetugas($petugas);

        return view('admin.petugas.form', [
            'petugas' => $petugas,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $petugas): RedirectResponse
    {
        $this->ensurePetugas($petugas);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($petugas->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $oldValues = [
            'name' => $petugas->name,
            'email' => $petugas->email,
            'phone' => $petugas->phone,
            'category_id' => $petugas->category_id,
        ];

        $released = 0;

        DB::transaction(function () use ($petugas, $data, &$released, &$oldValues) {
            if ((int) $data['category_id'] !== (int) $petugas->category_id) {
                $released = $this->releaseHeldReports($petugas);
            }

            $petugas->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'category_id' => $data['category_id'],
            ]);

            if (! empty($data['password'])) {
                $oldValues['password'] = 'diubah';
                $petugas->password = Hash::make($data['password']);
            }

            $petugas->save();
        });

        $newValues = [
            'name' => $petugas->name,
            'email' => $petugas->email,
            'phone' => $petugas->phone,
            'category_id' => $petugas->category_id,
        ];

        if (! empty($data['password'])) {
            $newValues['password'] = 'diubah';
        }

        $changed = array_filter($newValues, fn ($value, $key) => isset($oldValues[$key]) && $oldValues[$key] !== $value, ARRAY_FILTER_USE_BOTH);

        app(AuditLogger::class)->log(
            'petugas.updated',
            'petugas',
            $petugas->id,
            'Data petugas diperbarui.',
            array_intersect_key($oldValues, $changed),
            $changed
        );

        $message = 'Data petugas "' . $petugas->name . '" diperbarui.';
        if ($released > 0) {
            $message .= ' ' . $released . ' laporan yang dipegang dikembalikan ke antrean.';
        }

        return redirect()->route('admin.petugas.index')->with('success', $message);
    }

    public function toggle(User $petugas): RedirectResponse
    {
        $this->ensurePetugas($petugas);

        if (! $petugas->is_active) {
            $released = 0;
            $petugas->update(['is_active' => true]);

            app(AuditLogger::class)->log(
                'petugas.activated',
                'petugas',
                $petugas->id,
                'Petugas diaktifkan kembali.',
                ['is_active' => false],
                ['is_active' => true, 'released_reports' => $released]
            );

            return back()->with('success', 'Akun "' . $petugas->name . '" diaktifkan kembali.');
        }

        $released = 0;

        DB::transaction(function () use ($petugas, &$released) {
            $released = $this->releaseHeldReports($petugas);
            $petugas->update(['is_active' => false]);
        });

        app(AuditLogger::class)->log(
            'petugas.deactivated',
            'petugas',
            $petugas->id,
            'Petugas dinonaktifkan dan laporan dikembalikan ke antrean.',
            ['is_active' => true],
            ['is_active' => false, 'released_reports' => $released]
        );

        $message = 'Akun "' . $petugas->name . '" dinonaktifkan.';
        if ($released > 0) {
            $message .= ' ' . $released . ' laporan yang dipegang dikembalikan ke antrean.';
        }

        $remaining = User::where('role', User::ROLE_PETUGAS)
            ->where('category_id', $petugas->category_id)
            ->where('is_active', true)
            ->count();

        if ($remaining === 0) {
            return back()->with('error', $message . ' Peringatan: kategori ini tidak lagi memiliki petugas aktif.');
        }

        return back()->with('success', $message);
    }

    private function releaseHeldReports(User $petugas): int
    {
        return Report::where('petugas_id', $petugas->id)
            ->where('status', Report::STATUS_IN_PROGRESS)
            ->update([
                'status' => Report::STATUS_VALIDATED,
                'petugas_id' => null,
            ]);
    }

    private function ensurePetugas(User $user): void
    {
        abort_unless($user->role === User::ROLE_PETUGAS, 404);
    }
}
