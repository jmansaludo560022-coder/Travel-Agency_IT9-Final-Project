@extends('layouts.customer')

@section('title', 'Payment Details')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Payment #{{ $payment->id }}</h2>
        <a href="{{ route('customer.payments.index') }}"
            class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Back
        </a>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="space-y-3">
            <div><label class="text-sm font-medium text-gray-500">Package</label><p class="text-gray-900">{{ $payment->booking->travelPackage->package_name }}</p></div>
            <div><label class="text-sm font-medium text-gray-500">Amount Paid</label><p class="text-2xl font-bold text-gray-900">${{ number_format($payment->amount_paid, 2) }}</p></div>
            <div><label class="text-sm font-medium text-gray-500">Payment Method</label><p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p></div>
            <div><label class="text-sm font-medium text-gray-500">Payment Date</label><p class="text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</p></div>
            <div><label class="text-sm font-medium text-gray-500">Reference No.</label><p class="text-gray-900 font-mono">{{ $payment->payment_ref_no }}</p></div>
            <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1">
                    <span class="px-3 py-1 text-sm rounded-full
                        {{ $payment->payment_status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $payment->payment_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    @if($payment->paymentSchedules->count() > 0)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Schedules</h3>
            <table class="w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Due Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Amount Due</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payment->paymentSchedules as $schedule)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-sm font-semibold">${{ number_format($schedule->amount_due, 2) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $schedule->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $schedule->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $schedule->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
