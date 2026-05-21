<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account — TravelEase</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen">

    <!-- Full-screen background -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="Background"
            class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/65"></div>
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
            <a href="{{ route('login') }}" class="text-sm text-white/70 hover:text-white transition">
                Already have an account? <span class="text-sky-400 font-semibold">Log in</span>
            </a>
        </header>

        <!-- Form centered -->
        <div class="flex-1 flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-md">

                <!-- Card -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-2xl">

                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-extrabold text-white">Create your account</h1>
                        <p class="text-gray-400 text-sm mt-1">Join TravelEase and start exploring the world</p>
                    </div>

                    <!-- ── STEP INDICATOR ── -->
                    <div class="flex items-center mb-8">
                        <!-- Step 1 -->
                        <div class="flex items-center gap-2" id="step-indicator-1">
                            <div id="step-circle-1"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-sky-500 text-white transition-all">
                                1
                            </div>
                            <span id="step-label-1" class="text-sm font-semibold text-white">Personal Info</span>
                        </div>

                        <!-- Connector -->
                        <div class="flex-1 mx-3 h-px bg-white/20 relative">
                            <div id="step-connector" class="absolute inset-y-0 left-0 bg-sky-500 transition-all duration-500 w-0"></div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-center gap-2" id="step-indicator-2">
                            <div id="step-circle-2"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-white/20 text-gray-400 transition-all">
                                2
                            </div>
                            <span id="step-label-2" class="text-sm font-semibold text-gray-400">Account Setup</span>
                        </div>
                    </div>

                    <!-- Validation errors (server-side) -->
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-500/20 border border-red-400/30 text-red-300 text-sm rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <!-- ══ STEP 1: Personal Info ══ -->
                        <div id="step-1" class="space-y-4">

                            <!-- First / Middle / Last -->
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-300 mb-1.5">First Name</label>
                                    <input type="text" name="cus_fn" value="{{ old('cus_fn') }}" required
                                        class="w-full px-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Middle <span class="text-gray-500">(opt.)</span></label>
                                    <input type="text" name="cus_mn" value="{{ old('cus_mn') }}"
                                        class="w-full px-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-300 mb-1.5">Last Name</label>
                                    <input type="text" name="cus_ln" value="{{ old('cus_ln') }}" required
                                        class="w-full px-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
                                <input type="email" name="cus_email" value="{{ old('cus_email') }}" required
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Phone Number</label>
                                <input type="text" name="phone_num" value="{{ old('phone_num') }}" required
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                            </div>

                            <!-- Next button -->
                            <button type="button" onclick="goToStep2()"
                                class="w-full py-3.5 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-xl transition shadow-lg shadow-sky-500/30 mt-2">
                                Continue →
                            </button>
                        </div>

                        <!-- ══ STEP 2: Account Setup ══ -->
                        <div id="step-2" class="space-y-4 hidden">

                            <!-- Username -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Username</label>
                                <input type="text" name="username" value="{{ old('username') }}" required autocomplete="username"
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                                    placeholder="Choose a unique username">
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                                <input type="password" name="password" required autocomplete="new-password"
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                                    placeholder="At least 8 characters">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Confirm Password</label>
                                <input type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                                    placeholder="Repeat your password">
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-3 mt-2">
                                <button type="button" onclick="goToStep1()"
                                    class="flex-1 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/20 transition">
                                    ← Back
                                </button>
                                <button type="submit"
                                    class="flex-1 py-3.5 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-xl transition shadow-lg shadow-sky-500/30">
                                    Create Account
                                </button>
                            </div>
                        </div>

                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-sky-400 hover:text-sky-300 font-medium transition">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // If server returned errors, jump to step 2 if username/password fields have errors
        const serverErrors = @json($errors->keys());
        const step2Fields = ['username', 'password', 'password_confirmation'];
        const hasStep2Error = serverErrors.some(k => step2Fields.includes(k));

        if (hasStep2Error) {
            showStep(2);
        }

        function goToStep2() {
            // Basic client-side validation for step 1
            const fn = document.querySelector('[name="cus_fn"]').value.trim();
            const ln = document.querySelector('[name="cus_ln"]').value.trim();
            const email = document.querySelector('[name="cus_email"]').value.trim();
            const phone = document.querySelector('[name="phone_num"]').value.trim();

            if (!fn || !ln || !email || !phone) {
                alert('Please fill in all required fields before continuing.');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            showStep(2);
        }

        function goToStep1() {
            showStep(1);
        }

        function showStep(step) {
            const s1 = document.getElementById('step-1');
            const s2 = document.getElementById('step-2');
            const c1 = document.getElementById('step-circle-1');
            const c2 = document.getElementById('step-circle-2');
            const l1 = document.getElementById('step-label-1');
            const l2 = document.getElementById('step-label-2');
            const connector = document.getElementById('step-connector');

            if (step === 1) {
                s1.classList.remove('hidden');
                s2.classList.add('hidden');
                // Step 1 active
                c1.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-sky-500 text-white transition-all';
                l1.className = 'text-sm font-semibold text-white';
                // Step 2 inactive
                c2.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-white/20 text-gray-400 transition-all';
                l2.className = 'text-sm font-semibold text-gray-400';
                connector.style.width = '0%';
            } else {
                s1.classList.add('hidden');
                s2.classList.remove('hidden');
                // Step 1 completed
                c1.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-sky-500 text-white transition-all';
                c1.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`;
                l1.className = 'text-sm font-semibold text-sky-400';
                // Step 2 active
                c2.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-sky-500 text-white transition-all';
                c2.textContent = '2';
                l2.className = 'text-sm font-semibold text-white';
                connector.style.width = '100%';
                // Focus username
                setTimeout(() => document.querySelector('[name="username"]').focus(), 50);
            }
        }
    </script>

</body>
</html>
