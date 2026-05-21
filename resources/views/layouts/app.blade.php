<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'TravelEase'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');else document.documentElement.classList.remove('dark');})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200">

    <!-- Public Navbar -->
    <header class="sticky top-0 z-30 h-16 flex items-center bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
        <div class="max-w-7xl mx-auto w-full px-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <svg class="w-7 h-7 text-sky-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="font-bold text-gray-900 dark:text-white">TravelEase</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('packages.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5 transition">Packages</a>
                <a href="{{ route('faqs.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5 transition">FAQs</a>
            </nav>

            <div class="flex items-center gap-3">
                <x-theme-toggle />
                @auth
                    @php $role = auth()->user()->role; @endphp
                    <a href="{{ route($role . '.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-sky-600 hover:text-sky-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Log in</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold bg-sky-500 text-white rounded-full hover:bg-sky-600 transition">Sign up</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 dark:border-gray-800 py-3 mt-8">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between text-xs text-gray-400">
            <span>© {{ date('Y') }} TravelEase. All rights reserved.</span>
        </div>
    </footer>

</body>
</html>
