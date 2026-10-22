<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $hasReportsTable = Schema::hasTable('reports');
        $hasCategoriesTable = Schema::hasTable('categories');

        if (! $hasReportsTable || ! $hasCategoriesTable) {
            return view('landing', [
                'stats' => [
                    'total' => 0,
                    'completed' => 0,
                    'in_progress' => 0,
                    'avg_rating' => 0,
                ],
                'completedReports' => collect(),
                'categories' => collect(),
            ]);
        }

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
