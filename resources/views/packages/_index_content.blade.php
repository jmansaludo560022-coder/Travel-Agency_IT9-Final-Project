<div class="flex gap-6">

    {{-- Left Filter Sidebar --}}
    <aside class="w-52 flex-shrink-0">
        <form method="GET" action="{{ route('packages.index') }}" id="filter-form">
            <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-5">

                {{-- Search --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Search</p>
                    <div class="relative">
                        <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search packages..."
                            class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400">
                    </div>
                </div>

                {{-- Destination --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Destination</p>
                    <select name="destination_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400">
                        <option value="">All Destinations</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                                {{ $destination->city_name }}, {{ $destination->country }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Package Type --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Package Type</p>
                    <div class="space-y-1.5">
                        @foreach(['', 'Beach', 'Adventure', 'Cultural', 'Nature', 'City', 'Luxury', 'Family'] as $type)
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="radio" name="package_type" value="{{ $type }}"
                                    {{ request('package_type', '') === $type ? 'checked' : '' }}
                                    class="text-sky-600 focus:ring-indigo-400">
                                {{ $type === '' ? 'All Types' : $type }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Date Range --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Date Range</p>
                    <div class="space-y-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400">
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400">
                    </div>
                </div>

                {{-- Sort --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Sort By</p>
                    <select name="sort"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Earliest Date</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full py-2 bg-sky-500 text-white text-sm font-medium rounded-md hover:bg-sky-600 transition">
                    Apply Filters
                </button>
                <a href="{{ route('packages.index') }}"
                    class="block w-full text-center py-2 bg-gray-100 text-gray-600 text-sm rounded-md hover:bg-gray-200 transition">
                    Clear Filters
                </a>
            </div>
        </form>
    </aside>

    {{-- Package Results --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Tour Packages</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $packages->total() }} package(s) found</p>
            </div>
        </div>

        @if($packages->isEmpty())
            <div class="bg-white rounded-lg border border-gray-200 py-20 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-500 text-sm mb-4">No packages found. Try adjusting your filters.</p>
                <a href="{{ route('packages.index') }}"
                    class="inline-block px-5 py-2 bg-sky-500 text-white text-sm rounded-md hover:bg-sky-600">
                    Clear Filters
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($packages as $package)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition group">
                        @if($package->image)
                            <img src="{{ $package->image }}" alt="{{ $package->package_name }}"
                                class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-44 bg-indigo-50 flex items-center justify-center">
                                <svg class="w-10 h-10 text-indigo-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="text-sm font-semibold text-gray-800 leading-snug">{{ $package->package_name }}</h3>
                                @if($package->package_type)
                                    <span class="flex-shrink-0 px-2 py-0.5 text-xs bg-indigo-50 text-sky-600 rounded-full">{{ $package->package_type }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mb-2">
                                📍 {{ $package->destination->city_name }}, {{ $package->destination->country }}
                            </p>
                            <p class="text-xs text-gray-400 mb-3">
                                {{ \Carbon\Carbon::parse($package->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($package->end_date)->format('M d, Y') }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-sky-600">${{ number_format($package->package_cost, 2) }}</span>
                                <span class="text-xs text-gray-400">{{ $package->slots_available }} slots left</span>
                            </div>
                            <a href="{{ route('packages.show', $package->id) }}"
                                class="mt-3 block w-full text-center py-2 bg-sky-500 text-white text-sm rounded-md hover:bg-sky-600 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $packages->links() }}</div>
        @endif
    </div>
</div>
