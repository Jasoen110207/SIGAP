<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas dengan filter.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::query()->latest();

        // Filter Dari Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Filter Sampai Tanggal
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter Jenis Aksi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter Peran (Role)
        if ($request->filled('role')) {
            $query->where('user_role', $request->role);
        }

        // Filter Cari (Nama / IP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}