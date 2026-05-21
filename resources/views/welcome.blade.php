<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TravelEase — Your Journey Starts Here</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    <!-- ─── NAVBAR ─────────────────────────────────────────────── -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="text-xl font-bold tracking-tight text-white">TravelEase</span>
            </a>

            <!-- Nav links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-white/80">
                <a href="{{ route('packages.index') }}" class="hover:text-white transition">Packages</a>
                <a href="{{ route('faqs.index') }}" class="hover:text-white transition">FAQs</a>
            </nav>

            <!-- Auth buttons -->
            <div class="flex items-center gap-3">
                @auth
                    @php $role = auth()->user()->role; @endphp
                    <a href="{{ route($role . '.dashboard') }}"
                        class="px-4 py-2 text-sm font-semibold text-white hover:text-sky-300 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm font-semibold text-white/90 hover:text-white transition">
                        Log in
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 text-sm font-semibold bg-sky-500 text-white rounded-full hover:bg-sky-600 transition shadow-lg">
                        Sign up
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ─── HERO ──────────────────────────────────────────────── -->
    <section class="relative min-h-screen flex items-center overflow-hidden">

        <!-- Background image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="Travel destination"
                class="w-full h-full object-cover object-center">
            <!-- Gradient overlay: dark left, fades right -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-black/10"></div>
        </div>

        <!-- Hero content — left aligned -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 w-full">
            <div class="max-w-lg">
                <span class="inline-block px-3 py-1 text-xs font-semibold tracking-widest uppercase bg-sky-500/20 text-sky-300 border border-sky-400/30 rounded-full mb-6 backdrop-blur-sm">
                    ✈ Your Journey Starts Here
                </span>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight text-white mb-6">
                    Explore the World<br>
                    <span class="text-sky-400">Your Way.</span>
                </h1>
                <p class="text-lg text-gray-300 leading-relaxed mb-10">
                    Discover handpicked travel packages to the world's most breathtaking destinations. Book with confidence, travel with ease.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('packages.index') }}"
                        class="px-8 py-3.5 bg-sky-500 text-white font-semibold rounded-full hover:bg-sky-600 transition shadow-lg shadow-sky-500/30">
                        Browse Packages
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-8 py-3.5 bg-white/10 text-white font-semibold rounded-full border border-white/30 hover:bg-white/20 transition backdrop-blur-sm">
                        Get Started Free
                    </a>
                </div>

                <!-- Trust badges -->
                <div class="mt-12 flex flex-wrap items-center gap-6 text-sm">
                    @foreach(['Verified Packages', 'Secure Payments', '24/7 Support'] as $badge)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-300">{{ $badge }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Stats bar pinned to bottom of hero -->
        <div class="absolute bottom-0 inset-x-0 z-10 bg-black/40 backdrop-blur-sm border-t border-white/10">
            <div class="max-w-7xl mx-auto px-6 py-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                @foreach([['50+','Destinations'],['200+','Tour Packages'],['10K+','Happy Travelers'],['4.9★','Avg Rating']] as $stat)
                <div>
                    <p class="text-2xl font-extrabold text-sky-400">{{ $stat[0] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $stat[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ─── FOOTER ────────────────────────────────────────────── -->
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">

                <!-- Logo + tagline -->
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-sky-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                    <span class="text-white font-bold">TravelEase</span>
                    <span class="text-gray-600 hidden md:inline">—</span>
                    <span class="text-sm hidden md:inline">Making travel accessible for everyone.</span>
                </div>

                <!-- Copyright -->
                <p class="text-xs text-gray-600">© {{ date('Y') }} TravelEase. All rights reserved.</p>            </div>
        </div>
    </footer>

</body>
</html>
