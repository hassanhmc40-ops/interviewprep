<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-slate-500 mb-6">
                <a href="{{ route('domains.index') }}" class="hover:text-slate-700">Domains</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 font-medium">{{ $domain->name }}</span>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $domain->color }}"></div>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $domain->name }}</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('domains.edit', $domain) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-3 3m0 0l-3 3m3-3h8" />
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('domains.destroy', $domain) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors" onclick="return confirm('Are you sure? This will delete all concepts in this domain.')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-slate-900">{{ $concepts->count() }}</p>
                    <p class="text-sm text-slate-500">Total Concepts</p>
                </div>
                <div class="bg-emerald-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ $concepts->where('status', 'mastered')->count() }}</p>
                    <p class="text-sm text-emerald-600">Mastered</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600">{{ $concepts->where('status', 'in_progress')->count() }}</p>
                    <p class="text-sm text-amber-600">In Progress</p>
                </div>
            </div>

            <!-- Concepts Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-slate-900">Concepts</h2>
                <a href="{{ route('domain.concepts.create', $domain) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Concept
                </a>
            </div>

            <!-- Concepts List -->
            @forelse($concepts as $concept)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $concept->title }}</h3>
                            <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $concept->explanation }}</p>
                            <div class="flex items-center mt-3 space-x-3">
                                <x-difficulty-badge :difficulty="$concept->difficulty_level" />
                                <x-status-badge :status="$concept->status" />
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('concepts.show', $concept) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('concepts.edit', $concept) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-3 3m0 0l-3 3m3-3h8" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state
                    icon="document"
                    title="No concepts yet"
                    message="Add your first concept to start building your knowledge base."
                    actionText="Add Concept"
                    actionUrl="{{ route('domain.concepts.create', $domain) }}"
                />
            @endforelse
        </div>
    </div>
</x-app-layout>