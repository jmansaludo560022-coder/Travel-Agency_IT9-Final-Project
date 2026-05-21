<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In — TravelEase</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen">

    <!-- Full-screen background -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="Background"
            class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex flex-col">

        <!-- Navbar -->
        <header class="px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <svg class="w-7 h-7 text-sky-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="text-white font-bold text-lg">TravelEase</span>
            </a>
            <a href="{{ route('register') }}" class="text-sm text-white/70 hover:text-white transition">
                Don't have an account? <span class="text-sky-400 font-semibold">Sign up</span>
            </a>
        </header>

        <!-- Form centered -->
        <div class="flex-1 flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">

                <!-- Card -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-2xl">

                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-extrabold text-white">Welcome back</h1>
                        <p class="text-gray-400 text-sm mt-2">Log in to your TravelEase account</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 p-3 bg-green-500/20 border border-green-400/30 text-green-300 text-sm rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-300 mb-1.5">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                required autofocus autocomplete="username"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                            @error('username')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                            <input type="password" id="password" name="password"
                                required autocomplete="current-password"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember me + Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 rounded border-white/30 bg-white/10 text-sky-500 focus:ring-sky-500">
                                <span class="text-sm text-gray-400">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-sky-400 hover:text-sky-300 transition">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full py-3.5 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-xl transition shadow-lg shadow-sky-500/30 mt-2">
                            Log In
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        New to TravelEase?
                        <a href="{{ route('register') }}" class="text-sky-400 hover:text-sky-300 font-medium transition">Create an account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
