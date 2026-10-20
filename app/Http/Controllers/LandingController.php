<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Report::count(),
            'completed' => Report::where('status', Report::STATUS_COMPLETED)->count(),
            'in_progress' => Report::whereIn('status', [Report::STATUS_VALIDATED, Report::STATUS_IN_PROGRESS])->count(),
            'avg_rating' => round((float) Report::whereNotNull('rating')->avg('rating'), 1),
        ];

        $completedReports = Report::with(['category', 'user:id,name'])
            ->where('status', Report::STATUS_COMPLETED)
            ->whereNotNull('photo_after')
            ->latest('updated_at')
            ->take(7)
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('landing', compact('stats', 'completedReports', 'categories'));
    }
}
