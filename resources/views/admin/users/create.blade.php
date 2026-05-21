@extends('layouts.admin')
@section('title', 'Create User')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create New User</h2>
    </div>
    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-5">
        @csrf
        @php $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm'; $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5'; @endphp

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required class="{{ $input }}">
                @error('username')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Role</label>
                <select name="role" id="role" required onchange="toggleRoleFields()" class="{{ $input }}">
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
                @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Password</label>
                <input type="password" name="password" required class="{{ $input }}">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="{{ $input }}">
            </div>
        </div>

        <!-- Employee Fields -->
        <div id="employee-fields" class="hidden space-y-4 pt-4 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Employee Information</h3>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="{{ $label }}">First Name</label><input type="text" name="emp_fn" value="{{ old('emp_fn') }}" class="{{ $input }}">@error('emp_fn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
                <div><label class="{{ $label }}">Middle Name</label><input type="text" name="emp_mn" value="{{ old('emp_mn') }}" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">Last Name</label><input type="text" name="emp_ln" value="{{ old('emp_ln') }}" class="{{ $input }}">@error('emp_ln')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="{{ $label }}">Gender</label>
                    <select name="emp_gender" class="{{ $input }}">
                        <option value="">Select</option>
                        <option value="male" {{ old('emp_gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('emp_gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('emp_gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div><label class="{{ $label }}">Birthdate</label><input type="date" name="emp_birthdate" value="{{ old('emp_birthdate') }}" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">Hire Date</label><input type="date" name="emp_hiredate" value="{{ old('emp_hiredate') }}" class="{{ $input }}"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="{{ $label }}">Email</label><input type="email" name="emp_email" value="{{ old('emp_email') }}" class="{{ $input }}">@error('emp_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
                <div><label class="{{ $label }}">Contact Number</label><input type="text" name="emp_contact_num" value="{{ old('emp_contact_num') }}" class="{{ $input }}"></div>
            </div>
            <div><label class="{{ $label }}">Address</label><textarea name="emp_address" rows="2" class="{{ $input }}">{{ old('emp_address') }}</textarea></div>
            <div id="commission-field" class="hidden">
                <label class="{{ $label }}">Commission Rate (%)</label>
                <input type="number" name="commission_rate" step="0.01" min="0" max="100" value="{{ old('commission_rate', '0') }}" class="{{ $input }}" placeholder="e.g. 5 for 5%">
            </div>
        </div>

        <!-- Customer Fields -->
        <div id="customer-fields" class="hidden space-y-4 pt-4 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Customer Information</h3>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="{{ $label }}">First Name</label><input type="text" name="cus_fn" value="{{ old('cus_fn') }}" class="{{ $input }}">@error('cus_fn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
                <div><label class="{{ $label }}">Middle Name</label><input type="text" name="cus_mn" value="{{ old('cus_mn') }}" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">Last Name</label><input type="text" name="cus_ln" value="{{ old('cus_ln') }}" class="{{ $input }}">@error('cus_ln')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="{{ $label }}">Email</label><input type="email" name="cus_email" value="{{ old('cus_email') }}" class="{{ $input }}">@error('cus_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
                <div><label class="{{ $label }}">Phone Number</label><input type="text" name="phone_num" value="{{ old('phone_num') }}" class="{{ $input }}">@error('phone_num')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create User</button>
        </div>
    </form>
</div>
</div>
<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    document.getElementById('employee-fields').classList.toggle('hidden', !['admin','agent'].includes(role));
    document.getElementById('customer-fields').classList.toggle('hidden', role !== 'customer');
    document.getElementById('commission-field').classList.toggle('hidden', role !== 'agent');
}
document.addEventListener('DOMContentLoaded', toggleRoleFields);
</script>
@endsection
