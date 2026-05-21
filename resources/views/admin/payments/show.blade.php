@extends('layouts.admin')
@section('title', 'Payment Details')
@section('content')
<div class="max-w-2xl space-y-4">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Payment #{{ $payment->id }}</h2>
            <div class="flex gap-2">
                @if($payment->payment_status === 'pending')
                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition">Verify</button>
                    </form>
                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition">Reject</button>
                    </form>
                @endif
                <a href="{{ route('admin.payments.index') }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">Back</a>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Customer</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $payment->booking->customer->cus_fn }} {{ $payment->booking->customer->cus_ln }}</p></div>
            <div><p class="text-xs text-gray-500">Package</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $payment->booking->travelPackage->package_name }}</p></div>
            <div><p class="text-xs text-gray-500">Amount Paid</p><p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">${{ number_format($payment->amount_paid, 2) }}</p></div>
            <div><p class="text-xs text-gray-500">Status</p>
                @php $sc = ['verified'=>'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400','pending'=>'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400','rejected'=>'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400'][$payment->payment_status] ?? ''; @endphp
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full {{ $sc }}">{{ ucfirst($payment->payment_status) }}</span>
            </div>
            <div><p class="text-xs text-gray-500">Method</p><p class="text-gray-700 dark:text-gray-300 mt-0.5 capitalize">{{ str_replace('_',' ',$payment->payment_method) }}</p></div>
            <div><p class="text-xs text-gray-500">Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $payment->payment_date->format('F d, Y') }}</p></div>
            <div class="col-span-2"><p class="text-xs text-gray-500">Reference No.</p><p class="font-mono text-gray-900 dark:text-white mt-0.5">{{ $payment->payment_ref_no }}</p></div>
        </div>
    </div>

    @if($payment->paymentSchedules->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Schedules</h3>
        </div>
        <table class="w-full text-sm">
            <thead><tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                <th class="px-6 py-2 text-left text-xs text-gray-500">Due Date</th>
                <th class="px-6 py-2 text-left text-xs text-gray-500">Amount Due</th>
                <th class="px-6 py-2 text-left text-xs text-gray-500">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($payment->paymentSchedules as $s)
                <tr>
                    <td class="px-6 py-3 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($s->due_date)->format('M d, Y') }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-900 dark:text-white">${{ number_format($s->amount_due, 2) }}</td>
                    <td class="px-6 py-3">
                        @php $ss = ['paid'=>'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400','pending'=>'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400','overdue'=>'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400'][$s->status] ?? ''; @endphp
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
