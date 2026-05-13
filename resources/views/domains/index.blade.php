<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">My Domains</h1>
                    <p class="text-slate-600 mt-1">Organize your technical knowledge by topics</p>
                </div>
                <a href="{{ route('domains.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Domain
                </a>
            </div>

            <!-- Domains Grid -->
            @forelse($domains as $domain)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow mb-6">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $domain->color }}"></div>
                                <h2 class="text-xl font-semibold text-slate-900">{{ $domain->name }}</h2>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('domains.show', $domain) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('domains.edit', $domain) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-3 3m0 0l-3 3m3-3h8" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('domains.destroy', $domain) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this domain?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center text-sm text-slate-500">
                            <span class="font-medium">{{ $domain->concepts_count ?? $domain->concepts->count() }}</span>
                            <span class="mx-2">concepts</span>
                            <span class="mx-2">•</span>
                            <span class="font-medium text-emerald-600">{{ $domain->mastered_count ?? 0 }}</span>
                            <span class="mx-2">mastered</span>
                        </div>

                        <div class="mt-4">
                            @php
                                $total = $domain->concepts_count ?? $domain->concepts->count();
                                $mastered = $domain->mastered_count ?? 0;
                                $percentage = $total > 0 ? round(($mastered / $total) * 100) : 0;
                            @endphp
                            <x-progress-bar :percentage="$percentage" color="{{ $percentage >= 70 ? 'green' : ($percentage >= 30 ? 'amber' : 'blue') }}" />
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('domain.concepts', $domain) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
                                View Concepts
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state
                    icon="folder"
                    title="No domains yet"
                    message="Start by creating your first technical domain to organize your interview preparation."
                    actionText="Create Domain"
                    actionUrl="{{ route('domains.create') }}"
                />
            @endforelse
        </div>
    </div>
</x-app-layout>