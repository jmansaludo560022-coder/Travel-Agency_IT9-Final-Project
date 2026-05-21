<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = UserAccount::with(['employee', 'customer'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $isEmployee = in_array($request->input('role'), ['admin', 'agent']);

        $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:user_accounts,username'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:admin,agent,customer'],
            // Employee fields — required when role is admin or agent
            'emp_fn'          => [Rule::requiredIf($isEmployee), 'nullable', 'string', 'max:100'],
            'emp_mn'          => ['nullable', 'string', 'max:100'],
            'emp_ln'          => [Rule::requiredIf($isEmployee), 'nullable', 'string', 'max:100'],
            'emp_gender'      => [Rule::requiredIf($isEmployee), 'nullable', 'in:male,female,other'],
            'emp_birthdate'   => [Rule::requiredIf($isEmployee), 'nullable', 'date'],
            'emp_address'     => [Rule::requiredIf($isEmployee), 'nullable', 'string'],
            'emp_contact_num' => [Rule::requiredIf($isEmployee), 'nullable', 'string', 'max:30'],
            'emp_email'       => [Rule::requiredIf($isEmployee), 'nullable', 'email', 'unique:employees,emp_email'],
            'emp_hiredate'    => [Rule::requiredIf($isEmployee), 'nullable', 'date'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // Customer fields — required when role is customer
            'cus_fn'    => [Rule::requiredIf($request->input('role') === 'customer'), 'nullable', 'string', 'max:100'],
            'cus_mn'    => ['nullable', 'string', 'max:100'],
            'cus_ln'    => [Rule::requiredIf($request->input('role') === 'customer'), 'nullable', 'string', 'max:100'],
            'cus_email' => [Rule::requiredIf($request->input('role') === 'customer'), 'nullable', 'email', 'unique:customers,cus_email'],
            'phone_num' => [Rule::requiredIf($request->input('role') === 'customer'), 'nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($request) {
            if (in_array($request->role, ['admin', 'agent'])) {
                // 1. Create Employee first
                $employee = Employee::create([
                    'emp_fn'          => $request->emp_fn,
                    'emp_mn'          => $request->emp_mn,
                    'emp_ln'          => $request->emp_ln,
                    'emp_gender'      => $request->emp_gender,
                    'emp_birthdate'   => $request->emp_birthdate,
                    'emp_address'     => $request->emp_address,
                    'emp_contact_num' => $request->emp_contact_num,
                    'emp_email'       => $request->emp_email,
                    'emp_hiredate'    => $request->emp_hiredate,
                    'commission_rate' => ($request->commission_rate ?? 0) / 100,
                ]);

                // 2. Create UserAccount with employee_id FK
                UserAccount::create([
                    'username'    => $request->username,
                    'password'    => Hash::make($request->password),
                    'role'        => $request->role,
                    'employee_id' => $employee->id,
                    'customer_id' => null,
                ]);

            } elseif ($request->role === 'customer') {
                // 1. Create Customer first
                $customer = Customer::create([
                    'cus_fn'    => $request->cus_fn,
                    'cus_mn'    => $request->cus_mn,
                    'cus_ln'    => $request->cus_ln,
                    'cus_email' => $request->cus_email,
                    'phone_num' => $request->phone_num,
                ]);

                // 2. Create UserAccount with customer_id FK
                UserAccount::create([
                    'username'    => $request->username,
                    'password'    => Hash::make($request->password),
                    'role'        => $request->role,
                    'employee_id' => null,
                    'customer_id' => $customer->id,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = UserAccount::with(['employee', 'customer'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = UserAccount::with(['employee', 'customer'])->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = UserAccount::with(['employee', 'customer'])->findOrFail($id);

        $isEmployee = in_array($user->role, ['admin', 'agent']);

        $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:user_accounts,username,' . $id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            // Employee fields
            'emp_fn'          => [Rule::requiredIf($isEmployee), 'nullable', 'string', 'max:100'],
            'emp_mn'          => ['nullable', 'string', 'max:100'],
            'emp_ln'          => [Rule::requiredIf($isEmployee), 'nullable', 'string', 'max:100'],
            'emp_gender'      => [Rule::requiredIf($isEmployee), 'nullable', 'in:male,female,other'],
            'emp_birthdate'   => [Rule::requiredIf($isEmployee), 'nullable', 'date'],
            'emp_address'     => [Rule::requiredIf($isEmployee), 'nullable', 'string'],
            'emp_contact_num' => [Rule::requiredIf($isEmployee), 'nullable', 'string', 'max:30'],
            'emp_email'       => [Rule::requiredIf($isEmployee), 'nullable', 'email', 'unique:employees,emp_email,' . ($user->employee->id ?? 'NULL')],
            'emp_hiredate'    => [Rule::requiredIf($isEmployee), 'nullable', 'date'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // Customer fields
            'cus_fn'    => [Rule::requiredIf($user->role === 'customer'), 'nullable', 'string', 'max:100'],
            'cus_mn'    => ['nullable', 'string', 'max:100'],
            'cus_ln'    => [Rule::requiredIf($user->role === 'customer'), 'nullable', 'string', 'max:100'],
            'cus_email' => [Rule::requiredIf($user->role === 'customer'), 'nullable', 'email', 'unique:customers,cus_email,' . ($user->customer->id ?? 'NULL')],
            'phone_num' => [Rule::requiredIf($user->role === 'customer'), 'nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update(['username' => $request->username]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if (in_array($user->role, ['admin', 'agent']) && $user->employee) {
                $user->employee->update([
                    'emp_fn'          => $request->emp_fn,
                    'emp_mn'          => $request->emp_mn,
                    'emp_ln'          => $request->emp_ln,
                    'emp_gender'      => $request->emp_gender,
                    'emp_birthdate'   => $request->emp_birthdate,
                    'emp_address'     => $request->emp_address,
                    'emp_contact_num' => $request->emp_contact_num,
                    'emp_email'       => $request->emp_email,
                    'emp_hiredate'    => $request->emp_hiredate,
                    'commission_rate' => ($request->commission_rate ?? 0) / 100,
                ]);
            } elseif ($user->role === 'customer' && $user->customer) {
                $user->customer->update([
                    'cus_fn'    => $request->cus_fn,
                    'cus_mn'    => $request->cus_mn,
                    'cus_ln'    => $request->cus_ln,
                    'cus_email' => $request->cus_email,
                    'phone_num' => $request->phone_num,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = UserAccount::findOrFail($id);

        // Prevent archiving yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot archive your own account.');
        }

        DB::transaction(function () use ($user) {
            $user->delete(); // soft delete — sets deleted_at
        });

        return redirect()->route('admin.users.index')->with('success', 'User archived successfully.');
    }

    public function archive($id)
    {
        return $this->destroy($id);
    }

    public function restore($id)
    {
        $user = UserAccount::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->restore();
        });

        return redirect()->route('admin.users.archived')->with('success', 'User restored successfully.');
    }

    public function archived()
    {
        $users = UserAccount::onlyTrashed()
            ->with(['employee', 'customer'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.users.archived', compact('users'));
    }
}
