<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConceptRequest;
use App\Http\Requests\UpdateConceptRequest;
use App\Models\Concept;
use App\Models\Domain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConceptController extends Controller
{
    public function index(Request $request, Domain $domain): View
    {
        $this->authorizeConceptDomain($domain);

        $query = $domain->concepts()->with('generatedQuestions');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $concepts = $query->get();

        return view('concepts.index', compact('domain', 'concepts'));
    }

    public function create(Domain $domain): View
    {
        $this->authorizeConceptDomain($domain);

        return view('concepts.create', compact('domain'));
    }

    public function store(StoreConceptRequest $request, Domain $domain): RedirectResponse
    {
        $this->authorizeConceptDomain($domain);

        $domain->concepts()->create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'explanation' => $request->explanation,
            'difficulty_level' => $request->difficulty_level,
            'status' => $request->status,
        ]);

        return redirect()->route('domains.concepts.index', $domain)->with('success', 'Concept created successfully.');
    }

    public function show(Concept $concept): View
    {
        $this->authorizeConcept($concept);

        $concept->load('generatedQuestions', 'domain');

        return view('concepts.show', compact('concept'));
    }

    public function edit(Concept $concept): View
    {
        $this->authorizeConcept($concept);

        return view('concepts.edit', compact('concept'));
    }

    public function update(UpdateConceptRequest $request, Concept $concept): RedirectResponse
    {
        $this->authorizeConcept($concept);

        $concept->update($request->validated());

        return redirect()->route('concepts.show', $concept)->with('success', 'Concept updated successfully.');
    }

    public function destroy(Concept $concept): RedirectResponse
    {
        $this->authorizeConcept($concept);

        $concept->delete();

        return redirect()->route('domains.index')->with('success', 'Concept deleted successfully.');
    }

    public function toggleStatus(Concept $concept): JsonResponse
    {
        $this->authorizeConcept($concept);

        $statuses = ['to_review', 'in_progress', 'mastered'];
        $currentIndex = array_search($concept->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);

        $concept->update(['status' => $statuses[$nextIndex]]);

        return response()->json([
            'success' => true,
            'new_status' => $concept->status,
            'new_status_label' => $concept->statusLabel,
        ]);
    }

    private function authorizeConcept(Concept $concept): void
    {
        if ($concept->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeConceptDomain(Domain $domain): void
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }
    }
}