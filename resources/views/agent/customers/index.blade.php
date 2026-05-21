@extends('layouts.agent')
@section('title', 'Customers')
@section('content')

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">All Customers</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $customers->total() }} registered customers</p>
        </div>
        <a href="{{ route('agent.customers.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Customer
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-500/20 border border-green-200 dark:border-green-500/30 flex items-center justify-center flex-shrink-0">
                                <span class="text-green-600 dark:text-green-400 text-xs font-bold">
                                    {{ strtoupper(substr($customer->cus_fn, 0, 1)) }}{{ strtoupper(substr($customer->cus_ln, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $customer->cus_fn }} {{ $customer->cus_mn ? $customer->cus_mn . ' ' : '' }}{{ $customer->cus_ln }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $customer->cus_email }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $customer->phone_num }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">
                        {{ $customer->userAccount->username ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $customer->bookings_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('agent.customers.show', $customer->id) }}"
                                class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                            <a href="{{ route('agent.bookings.create', ['customer_id' => $customer->id]) }}"
                                class="text-xs text-green-600 dark:text-green-400 hover:underline">Book</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">
                        No customers yet.
                        <a href="{{ route('agent.customers.create') }}" class="text-sky-500 hover:underline ml-1">Create one now.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $customers->links() }}
    </div>
</div>
@endsection
