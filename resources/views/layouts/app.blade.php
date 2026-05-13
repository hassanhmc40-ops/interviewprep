<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InterviewPrep') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <span class="text-xl font-bold text-slate-800">InterviewPrep</span>
                            </a>
                        </div>

                        <!-- Desktop Navigation -->
                        @if(auth()->check())
                            <div class="hidden md:flex items-center space-x-6">
                                <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }} transition-colors">
                                    Dashboard
                                </a>
                                <a href="{{ route('domains.index') }}" class="text-sm font-medium {{ request()->routeIs('domains.*') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }} transition-colors">
                                    Domains
                                </a>
                            </div>

                            <!-- User Menu -->
                            <div class="hidden md:flex items-center">
                                <div x-data="{ dropdownOpen: false }" class="relative">
                                    <button @click="dropdownOpen = !dropdownOpen" class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                                        <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=3B82F6&background=E0F2FE" alt="{{ auth()->user()->name }}">
                                    </button>
                                    <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"></div>
                                    <div x-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 border border-slate-100">
                                        <div class="px-4 py-2 border-b border-slate-100">
                                            <p class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                        </div>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Log Out</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="hidden md:flex items-center space-x-4">
                                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Log in</a>
                                <a href="{{ route('register') }}" class="text-sm font-medium bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Get Started</a>
                            </div>
                        @endif

                        <!-- Mobile Menu Button -->
                        <div class="flex items-center md:hidden">
                            <button @click="open = !open" class="text-slate-600 hover:text-slate-900 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div x-show="open" class="md:hidden border-t border-slate-200 bg-white">
                    @if(auth()->check())
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">Dashboard</a>
                        <a href="{{ route('domains.index') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('domains.*') ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">Domains</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-slate-600">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-base font-medium text-slate-600">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-slate-600">Log in</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-blue-600 bg-blue-50">Get Started</a>
                    @endauth
                </div>
            </nav>

            <!-- Flash Messages -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-20 right-4 z-50">
                    <div class="bg-emerald-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-20 right-4 z-50">
                    <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-slate-500">&copy; {{ date('Y') }} InterviewPrep. Built with Laravel & Tailwind.</p>
                </div>
            </footer>
        </div>
    </body>
</html>