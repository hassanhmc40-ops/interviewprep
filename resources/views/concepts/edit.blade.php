<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <a href="{{ route('concepts.show', $concept) }}" class="hover:text-slate-700">{{ $concept->title }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 font-medium">Edit</span>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h1 class="text-2xl font-bold text-slate-900 mb-6">Edit Concept</h1>

                <form method="POST" action="{{ route('concepts.update', $concept) }}">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $concept->title) }}" required
                            class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('title')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Explanation -->
                    <div class="mb-6">
                        <label for="explanation" class="block text-sm font-medium text-slate-700 mb-1.5">Explanation</label>
                        <textarea name="explanation" id="explanation" rows="5" required
                            class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('explanation', $concept->explanation) }}</textarea>
                        @error('explanation')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Difficulty & Status -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="difficulty_level" class="block text-sm font-medium text-slate-700 mb-1.5">Difficulty</label>
                            <select name="difficulty_level" id="difficulty_level" required
                                class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="junior" {{ $concept->difficulty_level->value == 'junior' ? 'selected' : '' }}>Junior</option>
                                <option value="mid" {{ $concept->difficulty_level->value == 'mid' ? 'selected' : '' }}>Mid</option>
                                <option value="senior" {{ $concept->difficulty_level->value == 'senior' ? 'selected' : '' }}>Senior</option>
                            </select>
                            @error('difficulty_level')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select name="status" id="status" required
                                class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="to_review" {{ $concept->status->value == 'to_review' ? 'selected' : '' }}>To Review</option>
                                <option value="in_progress" {{ $concept->status->value == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="mastered" {{ $concept->status->value == 'mastered' ? 'selected' : '' }}>Mastered</option>
                            </select>
                            @error('status')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('concepts.show', $concept) }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>