@extends('layouts.agent')
@section('title', 'Account Settings')
@section('content')

<div class="max-w-lg mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Account Settings</h2>
        <p class="text-xs text-gray-500 mt-0.5">Update your username and password</p>
    </div>

    <form action="{{ route('agent.account.update') }}" method="POST" class="p-6 space-y-5">
        @csrf @method('PATCH')
        @php
            $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
            $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
        @endphp

        {{-- Read-only info --}}
        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 text-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center flex-shrink-0">
                    <span class="text-sky-600 dark:text-sky-400 text-sm font-bold">{{ strtoupper(substr($user->username, 0, 2)) }}</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->username }}</p>
                    <p class="text-xs text-gray-500">
                        Agent
                        @if($user->employee)
                            · {{ $user->employee->emp_fn }} {{ $user->employee->emp_ln }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Username --}}
        <div>
            <label class="{{ $label }}">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                required autocomplete="username" class="{{ $input }}">
            @error('username')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Change Password</p>
            <div class="space-y-4">
                <div>
                    <label class="{{ $label }}">New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                    <input type="password" name="password" autocomplete="new-password" class="{{ $input }}">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Confirm New Password</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password" class="{{ $input }}">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('agent.dashboard') }}"
                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
</div>
@endsection
