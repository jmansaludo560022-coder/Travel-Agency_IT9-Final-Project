@extends('layouts.admin')
@section('title', 'Employees')
@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('admin.employees.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition">
        Active
    </a>
    <a href="{{ route('admin.employees.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition flex items-center gap-2">
        Archived
        @if($archivedCount > 0)
            <span class="px-1.5 py-0.5 text-xs bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400 border border-red-200 dark:border-red-500/20 rounded-full">{{ $archivedCount }}</span>
        @endif
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Active Employees</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $employees->total() }} employees</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Employee
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hired</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($employees as $employee)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center flex-shrink-0">
                                <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr($employee->emp_fn, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $employee->emp_fn }} {{ $employee->emp_ln }}</p>
                                <p class="text-xs text-gray-400">{{ $employee->userAccount->username ?? '—' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php $role = $employee->userAccount->role ?? 'agent'; @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                            {{ $role === 'admin' ? 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20' : 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20' }}">
                            {{ ucfirst($role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $employee->emp_email }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $employee->emp_contact_num }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ number_format($employee->commission_rate * 100, 2) }}%</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($employee->emp_hiredate)->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.employees.show', $employee->id) }}" class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Edit</a>
                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Archive this employee?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-amber-600 dark:text-amber-400 hover:underline">Archive</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $employees->links() }}</div>
</div>
@endsection
