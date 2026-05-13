<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <span class="text-slate-900 font-medium">Edit</span>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h1 class="text-2xl font-bold text-slate-900 mb-6">Edit Domain</h1>

                <form method="POST" action="{{ route('domains.update', $domain) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Domain Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $domain->name) }}" required
                            class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="e.g., Laravel ORM, PHP OOP, MySQL">
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color Picker -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Badge Color</label>
                        <div class="grid grid-cols-6 gap-3">
                            @foreach(['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1', '#14B8A6', '#A855F7'] as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ $domain->color === $color ? 'checked' : '' }}>
                                    <div class="w-10 h-10 rounded-lg border-2 border-transparent hover:border-slate-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all flex items-center justify-center" style="background-color: {{ $color }}">
                                        <svg class="w-5 h-5 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('color')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('domains.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
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