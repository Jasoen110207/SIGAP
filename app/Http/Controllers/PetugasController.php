<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
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

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'category_id' => $data['category_id'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_PETUGAS,
            'is_active' => true,
        ]);

        return redirect()->route('petugas.index')
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

        $released = 0;

        DB::transaction(function () use ($petugas, $data, &$released) {
            // Pindah kategori: tugas yang sedang dipegang dikembalikan ke antrean
            // kategori lama, karena petugas tidak lagi berhak mengerjakannya.
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
                $petugas->password = Hash::make($data['password']);
            }

            $petugas->save();
        });

        $message = 'Data petugas "' . $petugas->name . '" diperbarui.';
        if ($released > 0) {
            $message .= ' ' . $released . ' laporan yang dipegang dikembalikan ke antrean kategori lama.';
        }

        return redirect()->route('petugas.index')->with('success', $message);
    }

    /**
     * Mengaktifkan / menonaktifkan akun petugas.
     * Saat dinonaktifkan, laporan yang sedang dipegang dikembalikan ke antrean.
     */
    public function toggle(User $petugas): RedirectResponse
    {
        $this->ensurePetugas($petugas);

        if (! $petugas->is_active) {
            $petugas->update(['is_active' => true]);

            return back()->with('success', 'Akun "' . $petugas->name . '" diaktifkan kembali.');
        }

        $released = 0;

        DB::transaction(function () use ($petugas, &$released) {
            $released = $this->releaseHeldReports($petugas);
            $petugas->update(['is_active' => false]);
        });

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
