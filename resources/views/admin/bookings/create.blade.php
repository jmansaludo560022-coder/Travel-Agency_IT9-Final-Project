@extends('layouts.admin')
@section('title', 'Create Booking')
@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create New Booking</h2>
                <p class="text-xs text-gray-500 mt-0.5">Book a tour package on behalf of a customer</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}"
                class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                Back
            </a>
        </div>

        <form action="{{ route('admin.bookings.store') }}" method="POST" id="bookingForm" class="p-6 space-y-6">
            @csrf
            @php
                $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
                $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
            @endphp

            {{-- Booking Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Customer</label>
                    <select name="customer_id" required class="{{ $input }}">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->cus_fn }} {{ $customer->cus_ln }}
                                @if($customer->userAccount) ({{ $customer->userAccount->username }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Package</label>
                    <select name="package_id" id="package_id" required onchange="updatePackageInfo()" class="{{ $input }}">
                        <option value="">Select Package</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}"
                                data-cost="{{ $package->package_cost }}"
                                data-slots="{{ $package->slots_available }}"
                                data-start="{{ $package->start_date }}"
                                data-end="{{ $package->end_date }}"
                                {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->package_name }} — {{ $package->destination->city_name }}
                                (${{ number_format($package->package_cost, 2) }}/pax · {{ $package->slots_available }} slots)
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Travel Date</label>
                    <input type="date" name="travel_date" id="travel_date" value="{{ old('travel_date') }}" required class="{{ $input }}">
                    @error('travel_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div id="package-info" class="hidden flex items-center gap-3 p-3 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-lg text-sm">
                    <svg class="w-5 h-5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sky-700 dark:text-sky-400 font-medium" id="pkg-cost-display"></p>
                        <p class="text-sky-600 dark:text-sky-500 text-xs" id="pkg-dates-display"></p>
                    </div>
                </div>
            </div>

            {{-- Travelers --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Travelers</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Total: <span id="total-amount" class="font-semibold text-sky-600 dark:text-sky-400">$0.00</span></p>
                    </div>
                    <button type="button" onclick="addTraveler()"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Add Traveler
                    </button>
                </div>

                <div id="travelers-container" class="space-y-4">
                    @php $ti = 0; $old_travelers = old('travelers', [[]]); @endphp
                    @foreach($old_travelers as $ti => $ot)
                    <div class="traveler-entry border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Traveler {{ $ti + 1 }}</span>
                            @if($ti > 0)
                            <button type="button" onclick="removeTraveler(this)" class="text-xs text-red-500 hover:text-red-700 transition">Remove</button>
                            @endif
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="{{ $label }}">First Name</label>
                                <input type="text" name="travelers[{{ $ti }}][trav_fn]" value="{{ $ot['trav_fn'] ?? '' }}" required class="{{ $input }}">
                                @error("travelers.{$ti}.trav_fn")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="{{ $label }}">Middle Name <span class="text-gray-400">(opt.)</span></label>
                                <input type="text" name="travelers[{{ $ti }}][trav_mn]" value="{{ $ot['trav_mn'] ?? '' }}" class="{{ $input }}">
                            </div>
                            <div>
                                <label class="{{ $label }}">Last Name</label>
                                <input type="text" name="travelers[{{ $ti }}][trav_ln]" value="{{ $ot['trav_ln'] ?? '' }}" required class="{{ $input }}">
                                @error("travelers.{$ti}.trav_ln")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="{{ $label }}">Birthdate</label>
                                <input type="date" name="travelers[{{ $ti }}][trav_birthdate]" value="{{ $ot['trav_birthdate'] ?? '' }}" required class="{{ $input }}">
                                @error("travelers.{$ti}.trav_birthdate")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="{{ $label }}">Gender</label>
                                <select name="travelers[{{ $ti }}][gender]" required class="{{ $input }}">
                                    <option value="male" {{ ($ot['gender'] ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ ($ot['gender'] ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ ($ot['gender'] ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="{{ $label }}">Nationality</label>
                                <input type="text" name="travelers[{{ $ti }}][nationality]" value="{{ $ot['nationality'] ?? '' }}" required class="{{ $input }}">
                                @error("travelers.{$ti}.nationality")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-3">
                                <label class="{{ $label }}">Passport No <span class="text-gray-400">(optional)</span></label>
                                <input type="text" name="travelers[{{ $ti }}][passport_no]" value="{{ $ot['passport_no'] ?? '' }}" class="{{ $input }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
let travelerCount = {{ count(old('travelers', [[]])) }};

const inputCls = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
const labelCls = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';

function updatePackageInfo() {
    const sel = document.getElementById('package_id');
    const opt = sel.options[sel.selectedIndex];
    const info = document.getElementById('package-info');
    if (!opt.value) { info.classList.add('hidden'); updateTotal(); return; }
    const cost = parseFloat(opt.dataset.cost || 0);
    document.getElementById('pkg-cost-display').textContent = '$' + cost.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' per person';
    document.getElementById('pkg-dates-display').textContent = opt.dataset.start + ' → ' + opt.dataset.end + ' · ' + opt.dataset.slots + ' slots left';
    info.classList.remove('hidden');
    updateTotal();
}

function updateTotal() {
    const sel = document.getElementById('package_id');
    const opt = sel.options[sel.selectedIndex];
    const cost = parseFloat(opt?.dataset?.cost || 0);
    const count = document.querySelectorAll('.traveler-entry').length;
    document.getElementById('total-amount').textContent = '$' + (cost * count).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function addTraveler() {
    const container = document.getElementById('travelers-container');
    const div = document.createElement('div');
    div.className = 'traveler-entry border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden';
    div.innerHTML = `
        <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Traveler ${travelerCount + 1}</span>
            <button type="button" onclick="removeTraveler(this)" class="text-xs text-red-500 hover:text-red-700 transition">Remove</button>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="${labelCls}">First Name</label><input type="text" name="travelers[${travelerCount}][trav_fn]" required class="${inputCls}"></div>
            <div><label class="${labelCls}">Middle Name <span class="text-gray-400">(opt.)</span></label><input type="text" name="travelers[${travelerCount}][trav_mn]" class="${inputCls}"></div>
            <div><label class="${labelCls}">Last Name</label><input type="text" name="travelers[${travelerCount}][trav_ln]" required class="${inputCls}"></div>
            <div><label class="${labelCls}">Birthdate</label><input type="date" name="travelers[${travelerCount}][trav_birthdate]" required class="${inputCls}"></div>
            <div><label class="${labelCls}">Gender</label><select name="travelers[${travelerCount}][gender]" required class="${inputCls}"><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
            <div><label class="${labelCls}">Nationality</label><input type="text" name="travelers[${travelerCount}][nationality]" required class="${inputCls}"></div>
            <div class="md:col-span-3"><label class="${labelCls}">Passport No <span class="text-gray-400">(optional)</span></label><input type="text" name="travelers[${travelerCount}][passport_no]" class="${inputCls}"></div>
        </div>`;
    container.appendChild(div);
    travelerCount++;
    updateTotal();
}

function removeTraveler(btn) {
    btn.closest('.traveler-entry').remove();
    updateTotal();
}

document.addEventListener('DOMContentLoaded', function () {
    updatePackageInfo();
    document.getElementById('package_id').addEventListener('change', updatePackageInfo);
});
</script>
@endsection
