<div class="mb-4">
    <a href="{{ route('packages.index') }}" class="text-sm text-sky-600 hover:underline">← Back to Packages</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- Main Content --}}
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800">
            @if($package->image)
                <img src="{{ Storage::url($package->image) }}" alt="{{ $package->package_name }}" class="w-full h-72 object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-full h-72 bg-sky-50 dark:bg-sky-500/10 items-center justify-center hidden">
                    <span class="text-sky-200 text-6xl">✈</span>
                </div>
            @else
                <div class="w-full h-72 bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center">
                    <span class="text-sky-200 text-6xl">✈</span>
                </div>
            @endif
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $package->package_name }}</h1>
                <p class="text-gray-500 mb-2">{{ $package->destination->city_name }}, {{ $package->destination->country }}</p>
                @if($package->package_type)
                    <span class="inline-block px-2 py-0.5 text-xs bg-sky-100 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 rounded">{{ $package->package_type }}</span>
                @endif
            </div>
        </div>

        @if($package->description)
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm">{{ $package->description }}</p>
        </div>
        @endif

        {{-- Itinerary — plain text, display line by line --}}
        @if($package->itinerary)
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Itinerary</h2>
            <div class="space-y-2">
                @foreach(explode("\n", trim($package->itinerary)) as $index => $line)
                    @if(trim($line))
                    <div class="flex items-start gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-800' : '' }}">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-sky-100 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 text-xs font-bold flex items-center justify-center">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed pt-1">{{ trim($line) }}</p>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Inclusions & Exclusions — plain text, display line by line --}}
        @if($package->inclusions || $package->exclusions)
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($package->inclusions)
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                        <span class="text-green-500">✓</span> Inclusions
                    </h2>
                    <ul class="space-y-1.5">
                        @foreach(explode("\n", trim($package->inclusions)) as $item)
                            @if(trim($item))
                            <li class="text-sm text-gray-700 dark:text-gray-300 flex items-start gap-2">
                                <span class="text-green-500 font-bold mt-0.5 flex-shrink-0">✓</span>
                                {{ trim($item) }}
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
                @if($package->exclusions)
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                        <span class="text-red-400">✗</span> Exclusions
                    </h2>
                    <ul class="space-y-1.5">
                        @foreach(explode("\n", trim($package->exclusions)) as $item)
                            @if(trim($item))
                            <li class="text-sm text-gray-700 dark:text-gray-300 flex items-start gap-2">
                                <span class="text-red-400 font-bold mt-0.5 flex-shrink-0">✗</span>
                                {{ trim($item) }}
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Booking Card --}}
    <div class="md:col-span-1">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm p-6 sticky top-6 border border-gray-100 dark:border-gray-800">
            <div class="mb-4">
                <span class="text-3xl font-bold text-sky-500">${{ number_format($package->package_cost, 2) }}</span>
                <span class="text-gray-500 text-sm"> / person</span>
            </div>
            <div class="space-y-2 mb-6 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ \Carbon\Carbon::parse($package->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($package->end_date)->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $package->slots_available }} slots available</span>
                </div>
                @if($package->package_type)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>{{ $package->package_type }}</span>
                </div>
                @endif
                @if(isset($package->reviews_avg_rating) && $package->reviews_count > 0)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>{{ number_format($package->reviews_avg_rating, 1) }} ({{ $package->reviews_count }} reviews)</span>
                </div>
                @endif
            </div>

            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.bookings.create', ['package_id' => $package->id]) }}"
                        class="block w-full text-center px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg transition text-sm">
                        Book Now
                    </a>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Only customers can book packages.</p>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="block w-full text-center px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg transition text-sm">
                    Login to Book
                </a>
            @endauth
        </div>
    </div>
</div>
