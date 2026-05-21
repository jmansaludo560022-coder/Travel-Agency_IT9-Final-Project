@extends('layouts.agent')
@section('title', 'Record Payment')
@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Record Payment</h2>
                <p class="text-xs text-gray-500 mt-0.5">Payment will be submitted as pending — admin must verify it</p>
            </div>
            <a href="{{ route('agent.payments.index') }}"
                class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                Back
            </a>
        </div>

        <form action="{{ route('agent.payments.store') }}" method="POST" id="paymentForm" class="p-6 space-y-5">
            @csrf
            @php
                $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
                $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
            @endphp

            {{-- Booking selector --}}
            <div>
                <label class="{{ $label }}">Booking</label>
                <select name="booking_id" id="booking_id" required onchange="onBookingChange()" class="{{ $input }}">
                    <option value="">Select a booking</option>
                    @foreach($bookings as $b)
                        @php $bal = $b->total_amount - $b->payments()->sum('amount_paid'); @endphp
                        <option value="{{ $b->id }}"
                            data-balance="{{ $bal }}"
                            data-total="{{ $b->total_amount }}"
                            data-paid="{{ $b->total_amount - $bal }}"
                            {{ (old('booking_id', $booking?->id) == $b->id) ? 'selected' : '' }}>
                            #{{ $b->id }} — {{ $b->customer->cus_fn }} {{ $b->customer->cus_ln }}
                            · {{ Str::limit($b->travelPackage->package_name, 25) }}
                            · Balance: ${{ number_format($bal, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('booking_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Booking summary --}}
            <div id="booking-summary" class="{{ $booking ? '' : 'hidden' }}">
                @php
                    $initBalance = $balance ?? 0;
                    $initTotal   = $booking?->total_amount ?? 0;
                    $initPaid    = $initTotal - $initBalance;
                    $initPct     = $initTotal > 0 ? min(100, ($initPaid / $initTotal) * 100) : 0;
                @endphp
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Total</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white" id="sum-total">${{ number_format($initTotal, 2) }}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-500/10 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Paid</p>
                        <p class="text-sm font-bold text-green-600 dark:text-green-400" id="sum-paid">${{ number_format($initPaid, 2) }}</p>
                    </div>
                    <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Balance</p>
                        <p class="text-sm font-bold text-amber-600 dark:text-amber-400" id="sum-balance">${{ number_format($initBalance, 2) }}</p>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full transition-all" id="sum-bar" style="width: {{ $initPct }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Amount to Pay ($)</label>
                    <input type="number" name="amount_paid" id="amount_paid"
                        step="0.01" min="0.01"
                        value="{{ old('amount_paid', $balance ? number_format($balance, 2, '.', '') : '') }}"
                        required oninput="updateAmountHint()"
                        class="{{ $input }}">
                    <p class="mt-1 text-xs text-gray-400" id="amount-hint">Select a booking first</p>
                    @error('amount_paid')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        onchange="toggleSchedules()" class="{{ $input }}">
                        <option value="cash"          {{ old('payment_method') === 'cash'          ? 'selected' : '' }}>Cash</option>
                        <option value="credit_card"   {{ old('payment_method') === 'credit_card'   ? 'selected' : '' }}>Credit Card</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="installment"   {{ old('payment_method') === 'installment'   ? 'selected' : '' }}>Installment</option>
                    </select>
                    @error('payment_method')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Payment Date</label>
                    <input type="date" name="payment_date"
                        value="{{ old('payment_date', now()->toDateString()) }}" required
                        class="{{ $input }}">
                    @error('payment_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $label }}">Reference Number</label>
                    <input type="text" name="payment_ref_no"
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
                                    value="{{ $os['due_date'] ?? '' }}" class="{{ $input }}">
                            </div>
                            <div>
                                <label class="{{ $label }}">Amount Due ($)</label>
                                <input type="number" name="schedules[{{ $si }}][amount_due]"
                                    step="0.01" min="0.01"
                                    value="{{ $os['amount_due'] ?? '' }}" class="{{ $input }}">
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

            {{-- Pending verification notice --}}
            <div class="flex items-start gap-3 p-3.5 rounded-lg bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 text-xs text-sky-700 dark:text-sky-400">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                This payment will be recorded with a <strong>Pending</strong> status. Admin must verify it before it is considered confirmed.
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
                <a href="{{ route('agent.payments.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let scheduleCount = {{ count(old('schedules', [[]])) }};
const inputCls = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
const labelCls = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';

function fmt(n) {
    return '$' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function onBookingChange() {
    const sel = document.getElementById('booking_id');
    const opt = sel.options[sel.selectedIndex];
    const summary = document.getElementById('booking-summary');

    if (!opt.value) {
        summary.classList.add('hidden');
        document.getElementById('amount_paid').value = '';
        document.getElementById('amount-hint').textContent = 'Select a booking first';
        document.getElementById('amount-hint').className = 'mt-1 text-xs text-gray-400';
        return;
    }

    const balance = parseFloat(opt.dataset.balance);
    const total   = parseFloat(opt.dataset.total);
    const paid    = parseFloat(opt.dataset.paid);
    const pct     = total > 0 ? Math.min(100, (paid / total) * 100) : 0;

    document.getElementById('sum-total').textContent   = fmt(total);
    document.getElementById('sum-paid').textContent    = fmt(paid);
    document.getElementById('sum-balance').textContent = fmt(balance);
    document.getElementById('sum-bar').style.width     = pct + '%';
    summary.classList.remove('hidden');

    document.getElementById('amount_paid').value = balance.toFixed(2);
    document.getElementById('amount_paid').max   = balance;
    updateAmountHint();
}

function updateAmountHint() {
    const sel = document.getElementById('booking_id');
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;

    const balance = parseFloat(opt.dataset.balance);
    const val     = parseFloat(document.getElementById('amount_paid').value) || 0;
    const hint    = document.getElementById('amount-hint');

    if (val > balance) {
        hint.textContent = '⚠ Exceeds remaining balance of ' + fmt(balance);
        hint.className   = 'mt-1 text-xs text-red-500';
    } else if (Math.abs(val - balance) < 0.001) {
        hint.textContent = '✓ Full payment — booking will be marked as fully paid';
        hint.className   = 'mt-1 text-xs text-green-600 dark:text-green-400';
    } else {
        hint.textContent = 'Remaining after this payment: ' + fmt(balance - val);
        hint.className   = 'mt-1 text-xs text-gray-400';
    }
}

function toggleSchedules() {
    const method = document.getElementById('payment_method').value;
    document.getElementById('schedules-section').classList.toggle('hidden', method !== 'installment');
}

function addSchedule() {
    const container = document.getElementById('schedules-container');
    const div = document.createElement('div');
    div.className = 'schedule-entry grid grid-cols-2 gap-4 p-4 relative border-t border-gray-100 dark:border-gray-800';
    div.innerHTML = `
        <div><label class="${labelCls}">Due Date</label><input type="date" name="schedules[${scheduleCount}][due_date]" class="${inputCls}"></div>
        <div><label class="${labelCls}">Amount Due ($)</label><input type="number" name="schedules[${scheduleCount}][amount_due]" step="0.01" min="0.01" class="${inputCls}"></div>
        <button type="button" onclick="removeSchedule(this)" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    container.appendChild(div);
    scheduleCount++;
}

function removeSchedule(btn) {
    btn.closest('.schedule-entry').remove();
}

document.addEventListener('DOMContentLoaded', function () {
    toggleSchedules();
    const sel = document.getElementById('booking_id');
    if (sel.value) onBookingChange();
});
</script>
@endsection
