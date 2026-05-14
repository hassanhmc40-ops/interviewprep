<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-slate-500 mb-6">
                <a href="{{ route('domains.index') }}" class="hover:text-slate-700">Domains</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('domains.show', $concept->domain) }}" class="hover:text-slate-700">{{ $concept->domain->name }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 font-medium">{{ $concept->title }}</span>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900/40 border border-green-700 rounded-xl text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-900/40 border border-red-700 rounded-xl text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Concept Details -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $concept->title }}</h1>
                        <div class="flex items-center mt-2 space-x-3">
                            <x-difficulty-badge :difficulty="$concept->difficulty_level" />
                            <x-status-badge :status="$concept->status" />
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
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

                <div class="border-t border-slate-200 pt-4">
                    <h2 class="text-sm font-semibold text-slate-700 mb-2">Explanation</h2>
                    <div class="text-slate-600 whitespace-pre-wrap">{{ $concept->explanation }}</div>
                </div>
            </div>

            <!-- AI Generation -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Interview Questions</h2>
                    <form method="POST" action="{{ route('concepts.generate', $concept) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Generate Questions
                        </button>
                    </form>
                </div>

                <!-- Generated Questions -->
                <div class="space-y-4">
                    @forelse($concept->generatedQuestions->groupBy(fn($q) => $q->generated_at->format('Y-m-d H:i:s')) as $timestamp => $questions)
                        <div class="border border-slate-200 rounded-lg overflow-hidden">
                            <div class="bg-slate-50 px-4 py-2 flex items-center justify-between">
                                <span class="text-sm text-slate-500">Generated {{ $questions->first()->generated_at->diffForHumans() }}</span>
                                <form method="POST" action="{{ route('generated-questions.destroy', $questions->first()) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-700" onclick="return confirm('Delete this generation?')">Delete</button>
                                </form>
                            </div>
                            <div class="p-4">
                                <ol class="list-decimal list-inside space-y-2">
                                    @foreach($questions as $question)
                                        <li class="text-slate-700">{{ $question->question }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-500">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <p class="font-medium text-slate-700 mb-1">No questions generated yet</p>
                            <p class="text-sm">Click the button above to generate 5 realistic Laravel interview questions.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>