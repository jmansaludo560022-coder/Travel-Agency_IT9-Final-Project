@extends('layouts.agent')
@section('title', 'Payment Details')
@section('content')

<div class="max-w-2xl mx-auto space-y-4">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Payment #{{ $payment->id }}</h2>
            <a href="{{ route('agent.payments.index') }}"
                class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                Back
            </a>
        </div>

        {{-- Pending notice --}}
        @if($payment->payment_status === 'pending')
        <div class="mx-6 mt-4 flex items-start gap-3 p-3.5 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-xs text-amber-700 dark:text-amber-400">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            This payment is awaiting verification by admin. Only admin can verify or reject payments.
        </div>
        @endif

        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500">Customer</p>
                <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                    {{ $payment->booking->customer->cus_fn }} {{ $payment->booking->customer->cus_ln }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Package</p>
                <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                    {{ $payment->booking->travelPackage->package_name }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Amount Paid</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">
                    ${{ number_format($payment->amount_paid, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Status</p>
                @php
                    $ps = [
                        'verified' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                        'pending'  => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                        'rejected' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                    ][$payment->payment_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                @endphp
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full border {{ $ps }}">
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Method</p>
                <p class="text-gray-700 dark:text-gray-300 mt-0.5 capitalize">
                    {{ str_replace('_', ' ', $payment->payment_method) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Payment Date</p>
                <p class="text-gray-700 dark:text-gray-300 mt-0.5">
                    {{ $payment->payment_date->format('F d, Y') }}
                </p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-gray-500">Reference No.</p>
                <p class="font-mono text-gray-900 dark:text-white mt-0.5">{{ $payment->payment_ref_no }}</p>
            </div>
        </div>
    </div>

    @if($payment->paymentSchedules->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Installment Schedules</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Due Date</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Amount Due</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($payment->paymentSchedules as $s)
                <tr>
                    <td class="px-6 py-3 text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($s->due_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($s->amount_due, 2) }}
                    </td>
                    <td class="px-6 py-3">
                        @php
                            $ss = [
                                'paid'    => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                                'overdue' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                            ][$s->status] ?? '';
                        @endphp
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $ss }}">{{ ucfirst($s->status) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
