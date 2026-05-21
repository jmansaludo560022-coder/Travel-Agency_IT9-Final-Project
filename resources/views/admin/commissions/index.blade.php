@extends('layouts.admin')
@section('title', 'Commissions')
@section('content')
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Employee Commission Rates</h2>
            <p class="text-xs text-gray-500 mt-0.5">Commission rates per employee</p>
        </div>
        <a href="{{ route('admin.commissions.report') }}" class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            View Report
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($employees as $employee)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center">
                                <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr($employee->emp_fn, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $employee->emp_fn }} {{ $employee->emp_ln }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $employee->emp_email }}</td>
                    <td class="px-6 py-4">
                        @php $role = $employee->userAccount->role ?? 'agent'; @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                            {{ $role === 'admin' ? 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20' : 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20' }}">
                            {{ ucfirst($role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ number_format($employee->commission_rate * 100, 2) }}%</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.commissions.report', ['employee_id' => $employee->id]) }}" class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View Report</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $employees->links() }}</div>
</div>
@endsection
