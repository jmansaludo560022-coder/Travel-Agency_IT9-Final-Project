<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — TravelEase</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Prevent flash of wrong theme -->
    <script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');else document.documentElement.classList.remove('dark');})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200 flex flex-col min-h-screen">

    <!-- ── TOP NAVBAR ─────────────────────────────────────── -->
    <header class="sticky top-0 z-30 h-16 flex items-center
        bg-white border-b border-gray-200
        dark:bg-gray-900 dark:border-gray-800">
        <div class="max-w-7xl mx-auto w-full px-6 flex items-center justify-between">

            <!-- Logo -->
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2">
                <svg class="w-7 h-7 text-sky-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="font-bold text-gray-900 dark:text-white">TravelEase</span>
            </a>

            <!-- Nav links -->
            <nav class="hidden md:flex items-center gap-1">
                @php
                    $navLinks = [
                        ['route' => 'customer.dashboard',      'label' => 'Dashboard',       'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'packages.index',          'label' => 'Browse Packages', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['route' => 'customer.bookings.index', 'label' => 'My Bookings',     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['route' => 'customer.payments.index', 'label' => 'Payments',        'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                        ['route' => 'customer.reviews.create', 'label' => 'Reviews',         'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                        ['route' => 'faqs.index',              'label' => 'FAQs',            'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp
                @foreach($navLinks as $link)
                    @php $active = request()->routeIs($link['route'] . '*'); @endphp
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-all
                            {{ $active
                                ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5' }}">
                        <svg style="width:16px;height:16px;flex-shrink:0"
                            class="{{ $active ? 'text-sky-500 dark:text-sky-400' : 'text-gray-400 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <!-- Right side: theme toggle + user dropdown -->
            <div class="flex items-center gap-2">
                <x-theme-toggle />

                <!-- User dropdown -->
                <div class="relative pl-2 border-l border-gray-200 dark:border-gray-700" id="user-dropdown-wrapper">
                    <button onclick="toggleUserDropdown()"
                        id="user-dropdown-btn"
                        class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center flex-shrink-0">
                            <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr(auth()->user()->username, 0, 2)) }}</span>
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->username }}</span>
                        <svg id="user-dropdown-chevron" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown panel -->
                    <div id="user-dropdown-panel"
                        class="hidden absolute right-0 mt-2 w-52 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-50 overflow-hidden">

                        {{-- User info header --}}
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->username }}</p>
                            @if(auth()->user()->customer)
                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                    {{ auth()->user()->customer->cus_fn }} {{ auth()->user()->customer->cus_ln }}
                                </p>
                            @endif
                        </div>

                        {{-- Profile link --}}
                        <a href="{{ route('customer.profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile Settings
                        </a>

                        <div class="border-t border-gray-100 dark:border-gray-800">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ── PAGE CONTENT ───────────────────────────────────── -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-6">

        <!-- Flash messages -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-3.5 rounded-lg text-sm mb-4
                bg-green-50 border border-green-200 text-green-700
                dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 p-3.5 rounded-lg text-sm mb-4
                bg-red-50 border border-red-200 text-red-700
                dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
    <footer class="border-t border-gray-200 dark:border-gray-800 py-3">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between text-xs text-gray-400">
            <span>© {{ date('Y') }} TravelEase. All rights reserved.</span>
            <span>Customer Portal</span>
        </div>
    </footer>

    <script>
        function toggleUserDropdown() {
            var panel   = document.getElementById('user-dropdown-panel');
            var chevron = document.getElementById('user-dropdown-chevron');
            if (!panel) return;
            var isOpen = !panel.classList.contains('hidden');
            panel.classList.toggle('hidden', isOpen);
            chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        }

        document.addEventListener('click', function(e) {
            var wrapper = document.getElementById('user-dropdown-wrapper');
            var panel   = document.getElementById('user-dropdown-panel');
            if (!wrapper || !panel) return;
            if (!wrapper.contains(e.target)) {
                panel.classList.add('hidden');
                var chevron = document.getElementById('user-dropdown-chevron');
                if (chevron) chevron.style.transform = '';
            }
        });
    </script>

</body>
</html>
