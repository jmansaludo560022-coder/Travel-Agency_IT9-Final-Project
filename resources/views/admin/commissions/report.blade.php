@extends('layouts.admin')
@section('title', 'Commission Report')
@section('content')
<div class="space-y-4">

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
        <form method="GET" action="{{ route('admin.commissions.report') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">From Date</label>
                <input type="date" name="from" value="{{ $from->toDateString() }}"
                    class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">To Date</label>
                <input type="date" name="to" value="{{ $to->toDateString() }}"
                    class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Employee</label>
                <select name="employee_id"
                    class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->emp_fn }} {{ $emp->emp_ln }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Generate</button>
            <a href="{{ route('admin.commissions.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">Back</a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500">Total Sales</p>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">${{ number_format($totalSales, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500">Total Commission</p>
            <p class="text-3xl font-extrabold text-green-600 dark:text-green-400 mt-1">${{ number_format($totalCommission, 2) }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                Report: {{ $from->format('M d, Y') }} — {{ $to->format('M d, Y') }}
            </h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Sales</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($report as $row)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $row['employee_name'] }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ number_format($row['commission_rate'] * 100, 2) }}%</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $row['bookings_count'] }}</td>
                    <td class="px-6 py-4 text-gray-900 dark:text-white">${{ number_format($row['total_sales'], 2) }}</td>
                    <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">${{ number_format($row['total_commission'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">No data for this period.</td></tr>
                @endforelse
            </tbody>
            @if(count($report) > 1)
            <tfoot>
                <tr class="border-t-2 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <td colspan="3" class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Totals</td>
                    <td class="px-6 py-3 font-bold text-gray-900 dark:text-white">${{ number_format($totalSales, 2) }}</td>
                    <td class="px-6 py-3 font-bold text-green-600 dark:text-green-400">${{ number_format($totalCommission, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
