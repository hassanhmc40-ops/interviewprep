<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-slate-500 mb-6">
                <a href="{{ route('domains.index') }}" class="hover:text-slate-700">Domains</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('domains.show', $domain) }}" class="hover:text-slate-700">{{ $domain->name }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 font-medium">Concepts</span>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $domain->color }}"></div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $domain->name }} - Concepts</h1>
                </div>
                <a href="{{ route('domains.concepts.create', $domain) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Concept
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="block w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="to_review" {{ request('status') == 'to_review' ? 'selected' : '' }}>To Review</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="mastered" {{ request('status') == 'mastered' ? 'selected' : '' }}>Mastered</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Difficulty</label>
                        <select name="difficulty" class="block w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Levels</option>
                            <option value="junior" {{ request('difficulty') == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ request('difficulty') == 'mid' ? 'selected' : '' }}>Mid</option>
                            <option value="senior" {{ request('difficulty') == 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                    @if(request('status') || request('difficulty'))
                        <a href="{{ route('domain.concepts', $domain) }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-900">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Concepts Table -->
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
                            <!-- Quick Status Toggle -->
                            <button
                                onclick="toggleStatus({{ $concept->id }})"
                                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Change Status"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
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
                            <form method="POST" action="{{ route('concepts.destroy', $concept) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Delete this concept?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state
                    icon="document"
                    title="No concepts found"
                    message="Start by adding concepts to this domain."
                    actionText="Add Concept"
                    actionUrl="{{ route('domains.concepts.create', $domain) }}"
                />
            @endforelse
        </div>
    </div>

    <script>
        function toggleStatus(conceptId) {
            fetch(`/concepts/${conceptId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</x-app-layout>