<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(): View
    {
        $domains = Domain::with(['concepts'])
            ->where('user_id', auth()->id())
            ->withCount('concepts')
            ->get()
            ->map(function ($domain) {
                $domain->mastered_count = $domain->concepts()->where('status', 'mastered')->count();
                return $domain;
            });

        return view('domains.index', compact('domains'));
    }

    public function create(): View
    {
        return view('domains.create');
    }

    public function store(StoreDomainRequest $request): RedirectResponse
    {
        Domain::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->route('domains.index')->with('success', 'Domain created successfully.');
    }

    public function show(Domain $domain): View
    {
        $this->authorizeDomain($domain);

        $concepts = $domain->concepts()
            ->with('generatedQuestions')
            ->get();

        return view('domains.show', compact('domain', 'concepts'));
    }

    public function edit(Domain $domain): View
    {
        $this->authorizeDomain($domain);

        return view('domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain): RedirectResponse
    {
        $this->authorizeDomain($domain);

        $domain->update($request->validated());

        return redirect()->route('domains.index')->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        $this->authorizeDomain($domain);

        $domain->delete();

        return redirect()->route('domains.index')->with('success', 'Domain deleted successfully.');
    }

    private function authorizeDomain(Domain $domain): void
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }
    }
}