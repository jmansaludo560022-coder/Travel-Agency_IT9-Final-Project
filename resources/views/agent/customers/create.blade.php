@extends('layouts.agent')
@section('title', 'Create Customer')
@section('content')

<div class="max-w-2xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create Customer Account</h2>
            <p class="text-xs text-gray-500 mt-0.5">Register a new customer so you can book packages on their behalf</p>
        </div>
        <a href="{{ route('agent.customers.index') }}"
            class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Back
        </a>
    </div>

    <form action="{{ route('agent.customers.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @php
            $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
            $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
        @endphp

        {{-- Personal Information --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Personal Information</h3>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="{{ $label }}">First Name</label>
                    <input type="text" name="cus_fn" value="{{ old('cus_fn') }}" required class="{{ $input }}">
                    @error('cus_fn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Middle Name <span class="text-gray-400">(opt.)</span></label>
                    <input type="text" name="cus_mn" value="{{ old('cus_mn') }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">Last Name</label>
                    <input type="text" name="cus_ln" value="{{ old('cus_ln') }}" required class="{{ $input }}">
                    @error('cus_ln')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Email Address</label>
                    <input type="email" name="cus_email" value="{{ old('cus_email') }}" required class="{{ $input }}">
                    @error('cus_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Phone Number</label>
                    <input type="text" name="phone_num" value="{{ old('phone_num') }}" required class="{{ $input }}" placeholder="e.g. 09171234567">
                    @error('phone_num')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Account Credentials --}}
        <div class="pt-5 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Login Credentials</h3>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="{{ $label }}">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required autocomplete="off" class="{{ $input }}">
                    @error('username')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="{{ $label }}">Password</label>
                        <input type="password" name="password" required autocomplete="new-password" class="{{ $input }}">
                        @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="{{ $label }}">Confirm Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" class="{{ $input }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('agent.customers.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                Create Customer
            </button>
        </div>
    </form>
</div>
</div>
@endsection
