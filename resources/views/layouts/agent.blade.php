<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — TravelEase Agent</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script>
        (function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');else document.documentElement.classList.remove('dark');})();

        window.toggleTheme = function () {
            var next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            if (next === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            var sun  = document.getElementById('icon-sun');
            var moon = document.getElementById('icon-moon');
            if (sun && moon) {
                sun.classList.toggle('hidden', next !== 'dark');
                moon.classList.toggle('hidden', next === 'dark');
            }
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 flex flex-col fixed inset-y-0 left-0 z-30 bg-white border-r border-gray-200 dark:bg-gray-900 dark:border-gray-800">
        <div class="h-16 flex items-center gap-3 px-6 border-b border-gray-200 dark:border-gray-800">
            <svg class="w-7 h-7 text-sky-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
            </svg>
            <div>
                <p class="font-bold text-sm text-gray-900 dark:text-white">TravelEase</p>
                <p class="text-xs text-gray-400">Agent Panel</p>
            </div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $nav = [
                    ['route'=>'agent.dashboard',       'label'=>'Dashboard', 'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route'=>'agent.packages.index',  'label'=>'Packages',  'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['route'=>'agent.bookings.index',  'label'=>'Bookings',  'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['route'=>'agent.payments.index',  'label'=>'Payments',  'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['route'=>'agent.customers.index', 'label'=>'Customers', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ];
            @endphp
            @foreach($nav as $item)
                @php $active = request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                        {{ $active ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5' }}">
                    <svg style="width:18px;height:18px;flex-shrink:0" class="{{ $active ? 'text-sky-500 dark:text-sky-400' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                    @if($active)<span class="ml-auto w-1.5 h-1.5 rounded-full bg-sky-500 dark:bg-sky-400"></span>@endif
                </a>
            @endforeach
        </nav>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center flex-shrink-0">
                    <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr(auth()->user()->username, 0, 2)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->username }}</p>
                    <a href="{{ route('agent.account.edit') }}" class="text-xs text-sky-500 hover:text-sky-400 transition">Account Settings</a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="h-16 sticky top-0 z-20 flex items-center justify-between px-6 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
            <div>
                <h1 class="text-base font-semibold text-gray-900 dark:text-white">@yield('title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400">TravelEase Agent</p>
            </div>
            <div class="flex items-center gap-2">
                <x-theme-toggle />
                <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center">
                    <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr(auth()->user()->username, 0, 2)) }}</span>
                </div>
            </div>
        </header>
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
                <div class="flex items-center gap-3 p-3.5 rounded-lg text-sm bg-green-50 border border-green-200 text-green-700 dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 p-3.5 rounded-lg text-sm bg-red-50 border border-red-200 text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <main class="flex-1 p-6">@yield('content')</main>
        <footer class="px-6 py-3 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-400 flex items-center justify-between">
            <span>© {{ date('Y') }} TravelEase.</span><span>Agent Portal</span>
        </footer>
    </div>
</div>
</body>
</html>
