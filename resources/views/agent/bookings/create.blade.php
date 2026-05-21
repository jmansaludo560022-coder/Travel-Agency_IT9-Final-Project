@extends('layouts.agent')
@section('title', 'Create Booking')
@section('content')

<div class="max-w-3xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create New Booking</h2>
            <p class="text-xs text-gray-500 mt-0.5">Book a tour package on behalf of a customer</p>
        </div>
        <a href="{{ route('agent.bookings.index') }}"
            class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Back
        </a>
    </div>

    <form action="{{ route('agent.bookings.store') }}" method="POST" id="bookingForm" class="p-6 space-y-6">
        @csrf
        @php
            $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
            $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
        @endphp

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
                <select name="package_id" id="package_id" required onchange="updateTotal()" class="{{ $input }}">
                    <option value="">Select Package</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" data-cost="{{ $package->package_cost }}"
                            {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->package_name }} — {{ $package->destination->city_name }}
                            (${{ number_format($package->package_cost, 2) }}/pax)
                        </option>
                    @endforeach
                </select>
                @error('package_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="{{ $label }}">Travel Date</label>
                <input type="date" name="travel_date" value="{{ old('travel_date') }}" required class="{{ $input }}">
                @error('travel_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
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
                <div class="traveler-entry border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Traveler 1</span>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="{{ $label }}">First Name</label><input type="text" name="travelers[0][trav_fn]" required class="{{ $input }}"></div>
                        <div><label class="{{ $label }}">Middle Name <span class="text-gray-400">(opt.)</span></label><input type="text" name="travelers[0][trav_mn]" class="{{ $input }}"></div>
                        <div><label class="{{ $label }}">Last Name</label><input type="text" name="travelers[0][trav_ln]" required class="{{ $input }}"></div>
                        <div><label class="{{ $label }}">Birthdate</label><input type="date" name="travelers[0][trav_birthdate]" required class="{{ $input }}"></div>
                        <div><label class="{{ $label }}">Gender</label>
                            <select name="travelers[0][gender]" required class="{{ $input }}">
                                <option value="male">Male</option><option value="female">Female</option><option value="other">Other</option>
                            </select>
                        </div>
                        <div><label class="{{ $label }}">Nationality</label><input type="text" name="travelers[0][nationality]" required class="{{ $input }}"></div>
                        <div class="md:col-span-3"><label class="{{ $label }}">Passport No <span class="text-gray-400">(opt.)</span></label><input type="text" name="travelers[0][passport_no]" class="{{ $input }}"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('agent.bookings.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create Booking</button>
        </div>
    </form>
</div>
</div>

<script>
let travelerCount = 1;
const inputCls = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
const labelCls = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';

function updateTotal() {
    const sel = document.getElementById('package_id');
    const cost = parseFloat(sel.options[sel.selectedIndex]?.dataset?.cost || 0);
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
            <div class="md:col-span-3"><label class="${labelCls}">Passport No <span class="text-gray-400">(opt.)</span></label><input type="text" name="travelers[${travelerCount}][passport_no]" class="${inputCls}"></div>
        </div>`;
    container.appendChild(div);
    travelerCount++;
    updateTotal();
}

function removeTraveler(btn) {
    btn.closest('.traveler-entry').remove();
    updateTotal();
}
</script>
@endsection
