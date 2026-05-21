@extends('layouts.customer')
@section('title', 'Make Payment')
@section('content')

<div class="max-w-2xl mx-auto space-y-4">

    {{-- Booking Summary Card --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-500 mb-0.5">Booking #{{ $booking->id }}</p>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $booking->travelPackage->package_name }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}</p>
            </div>
            <a href="{{ route('customer.bookings.show', $booking->id) }}"
                class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                Back
            </a>
        </div>

        {{-- Balance bar --}}
        @php
            $totalPaid = $booking->total_amount - $balance;
            $paidPct   = $booking->total_amount > 0 ? min(100, ($totalPaid / $booking->total_amount) * 100) : 0;
        @endphp
        <div class="mt-4 grid grid-cols-3 gap-3 text-center">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                <p class="text-xs text-gray-500 mb-0.5">Total</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-500/10 rounded-lg p-3">
                <p class="text-xs text-gray-500 mb-0.5">Paid</p>
                <p class="text-sm font-bold text-green-600 dark:text-green-400">${{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3">
                <p class="text-xs text-gray-500 mb-0.5">Balance</p>
                <p class="text-sm font-bold text-amber-600 dark:text-amber-400">${{ number_format($balance, 2) }}</p>
            </div>
        </div>
        <div class="mt-3">
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-green-500 h-1.5 rounded-full transition-all" style="width: {{ $paidPct }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1 text-right">{{ number_format($paidPct, 0) }}% paid</p>
        </div>
    </div>

    {{-- Payment Form --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Payment Details</h3>
        </div>

        <form action="{{ route('customer.payments.store') }}" method="POST" id="paymentForm" class="p-6 space-y-5">
            @csrf
            @php
                $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
                $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
            @endphp

            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Amount to Pay ($)</label>
                    <div class="relative">
                        <input type="number" name="amount_paid" id="amount_paid"
                            step="0.01" min="0.01" max="{{ $balance }}"
                            value="{{ old('amount_paid', number_format($balance, 2, '.', '')) }}"
                            required oninput="updateAmountHint()"
                            class="{{ $input }}">
                    </div>
                    <p class="mt-1 text-xs text-gray-400" id="amount-hint">
                        Max: ${{ number_format($balance, 2) }} remaining
                    </p>
                    @error('amount_paid')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        onchange="toggleSchedules()"
                        class="{{ $input }}">
                        <option value="cash"          {{ old('payment_method') === 'cash'          ? 'selected' : '' }}>Cash</option>
                        <option value="credit_card"   {{ old('payment_method') === 'credit_card'   ? 'selected' : '' }}>Credit Card</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="installment"   {{ old('payment_method') === 'installment'   ? 'selected' : '' }}>Installment</option>
                    </select>
                    @error('payment_method')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date"
                        value="{{ old('payment_date', now()->toDateString()) }}" required
                        class="{{ $input }}">
                    @error('payment_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Reference Number</label>
                    <input type="text" name="payment_ref_no" id="payment_ref_no"
                        value="{{ old('payment_ref_no') }}" required
                        placeholder="e.g., TXN-20260520-001"
                        class="{{ $input }}">
                    @error('payment_ref_no')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Installment Schedules --}}
            <div id="schedules-section" class="{{ old('payment_method') === 'installment' ? '' : 'hidden' }}">
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Installment Schedules</h4>
                        <button type="button" onclick="addSchedule()"
                            class="flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Add
                        </button>
                    </div>
                    <div id="schedules-container" class="divide-y divide-gray-100 dark:divide-gray-800">
                        @php $oldSchedules = old('schedules', [[]]); @endphp
                        @foreach($oldSchedules as $si => $os)
                        <div class="schedule-entry grid grid-cols-2 gap-4 p-4 relative">
                            <div>
                                <label class="{{ $label }}">Due Date</label>
                                <input type="date" name="schedules[{{ $si }}][due_date]"
                                    value="{{ $os['due_date'] ?? '' }}"
                                    class="{{ $input }}">
                                @error("schedules.{$si}.due_date")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="{{ $label }}">Amount Due ($)</label>
                                <input type="number" name="schedules[{{ $si }}][amount_due]"
                                    step="0.01" min="0.01"
                                    value="{{ $os['amount_due'] ?? '' }}"
                                    class="{{ $input }}">
                                @error("schedules.{$si}.amount_due")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            @if($si > 0)
                            <button type="button" onclick="removeSchedule(this)"
                                class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @error('schedules')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
                <a href="{{ route('customer.bookings.show', $booking->id) }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                    Submit Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let scheduleCount = {{ count(old('schedules', [[]])) }};
const inputCls = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
const labelCls = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
const balance  = {{ $balance }};

function toggleSchedules() {
    const method = document.getElementById('payment_method').value;
    document.getElementById('schedules-section').classList.toggle('hidden', method !== 'installment');
}

function updateAmountHint() {
    const val = parseFloat(document.getElementById('amount_paid').value) || 0;
    const remaining = balance - val;
    const hint = document.getElementById('amount-hint');
    if (val > balance) {
        hint.textContent = '⚠ Exceeds remaining balance of $' + balance.toFixed(2);
        hint.className = 'mt-1 text-xs text-red-500';
    } else if (val === balance) {
        hint.textContent = '✓ Full payment — booking will be marked as fully paid';
        hint.className = 'mt-1 text-xs text-green-600 dark:text-green-400';
    } else {
        hint.textContent = 'Remaining after this payment: $' + remaining.toFixed(2);
        hint.className = 'mt-1 text-xs text-gray-400';
    }
}

function addSchedule() {
    const container = document.getElementById('schedules-container');
    const div = document.createElement('div');
    div.className = 'schedule-entry grid grid-cols-2 gap-4 p-4 relative border-t border-gray-100 dark:border-gray-800';
    div.innerHTML = `
        <div>
            <label class="${labelCls}">Due Date</label>
            <input type="date" name="schedules[${scheduleCount}][due_date]" class="${inputCls}">
        </div>
        <div>
            <label class="${labelCls}">Amount Due ($)</label>
            <input type="number" name="schedules[${scheduleCount}][amount_due]" step="0.01" min="0.01" class="${inputCls}">
        </div>
        <button type="button" onclick="removeSchedule(this)"
            class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    container.appendChild(div);
    scheduleCount++;
}

function removeSchedule(btn) {
    btn.closest('.schedule-entry').remove();
}

document.addEventListener('DOMContentLoaded', function () {
    toggleSchedules();
    updateAmountHint();
});
</script>
@endsection
