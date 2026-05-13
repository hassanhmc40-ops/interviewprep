<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Concept;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $totalConcepts = Concept::where('user_id', $userId)->count();
        $toReview = Concept::where('user_id', $userId)->where('status', 'to_review')->count();
        $inProgress = Concept::where('user_id', $userId)->where('status', 'in_progress')->count();
        $mastered = Concept::where('user_id', $userId)->where('status', 'mastered')->count();

        $domains = Domain::with('concepts')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($domain) {
                $total = $domain->concepts->count();
                $mastered = $domain->concepts->where('status', 'mastered')->count();
                $domain->progress_percentage = $total > 0 ? round(($mastered / $total) * 100) : 0;
                return $domain;
            });

        $bestDomain = $domains->sortByDesc('progress_percentage')->first();
        $needsRevisionDomain = $domains->sortBy('progress_percentage')->first();

        return view('dashboard', compact(
            'totalConcepts',
            'toReview',
            'inProgress',
            'mastered',
            'domains',
            'bestDomain',
            'needsRevisionDomain'
        ));
    }
}