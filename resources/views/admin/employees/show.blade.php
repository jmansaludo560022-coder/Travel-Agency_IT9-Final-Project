@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $employee->emp_fn }} {{ $employee->emp_mn ? $employee->emp_mn . ' ' : '' }}{{ $employee->emp_ln }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Employee details</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.employees.edit', $employee->id) }}"
                   class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Edit</a>
                <a href="{{ route('admin.employees.index') }}"
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 transition">Back</a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Account</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Username</dt>
                        <dd class="text-gray-900 dark:text-gray-200 font-medium">{{ $employee->userAccount->username }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Role</dt>
                        <dd>
                            @php
                                $roleColor = $employee->userAccount->role === 'admin'
                                    ? 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20'
                                    : 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20';
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $roleColor }}">
                                {{ ucfirst($employee->userAccount->role) }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Last Login</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ $employee->userAccount->last_login ? $employee->userAccount->last_login->format('Y-m-d H:i') : 'Never' }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Personal</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Gender</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ ucfirst($employee->emp_gender) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Birthdate</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ $employee->emp_birthdate->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Hire Date</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ $employee->emp_hiredate->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Commission Rate</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ number_format($employee->commission_rate * 100, 2) }}%</dd>
                    </div>
                </dl>
            </div>

            <div class="md:col-span-2 border-t border-gray-100 dark:border-gray-800 pt-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Contact</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ $employee->emp_email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Contact Number</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ $employee->emp_contact_num }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Address</dt>
                        <dd class="text-gray-700 dark:text-gray-200">{{ $employee->emp_address }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
