@extends('layouts.admin')
@section('title', 'User Details')
@section('content')
<div class="max-w-2xl">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">User Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">Edit</a>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">Back</a>
        </div>
    </div>
    <div class="p-6 space-y-6">
        <!-- Account -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Account</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs text-gray-500">Username</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $user->username }}</p></div>
                <div><p class="text-xs text-gray-500">Role</p>
                    @php $rc = ['admin'=>'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400','agent'=>'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400','customer'=>'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400'][$user->role] ?? ''; @endphp
                    <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full {{ $rc }}">{{ ucfirst($user->role) }}</span>
                </div>
                <div><p class="text-xs text-gray-500">Last Login</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}</p></div>
                <div><p class="text-xs text-gray-500">Created</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->created_at->format('M d, Y') }}</p></div>
            </div>
        </div>

        @if($user->employee)
        <div class="border-t border-gray-100 dark:border-gray-800 pt-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Employee Info</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs text-gray-500">Full Name</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $user->employee->emp_fn }} {{ $user->employee->emp_mn }} {{ $user->employee->emp_ln }}</p></div>
                <div><p class="text-xs text-gray-500">Email</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->employee->emp_email }}</p></div>
                <div><p class="text-xs text-gray-500">Contact</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->employee->emp_contact_num }}</p></div>
                <div><p class="text-xs text-gray-500">Commission Rate</p><p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ number_format($user->employee->commission_rate * 100, 2) }}%</p></div>
                <div><p class="text-xs text-gray-500">Hire Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->employee->emp_hiredate }}</p></div>
                <div><p class="text-xs text-gray-500">Gender</p><p class="text-gray-700 dark:text-gray-300 mt-0.5 capitalize">{{ $user->employee->emp_gender }}</p></div>
            </div>
        </div>
        @endif

        @if($user->customer)
        <div class="border-t border-gray-100 dark:border-gray-800 pt-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Customer Info</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs text-gray-500">Full Name</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $user->customer->cus_fn }} {{ $user->customer->cus_mn }} {{ $user->customer->cus_ln }}</p></div>
                <div><p class="text-xs text-gray-500">Email</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->customer->cus_email }}</p></div>
                <div><p class="text-xs text-gray-500">Phone</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $user->customer->phone_num }}</p></div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@endsection
