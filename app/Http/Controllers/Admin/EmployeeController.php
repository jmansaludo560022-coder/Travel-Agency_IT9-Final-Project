<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('userAccount')->paginate(15);
        $archivedCount = Employee::onlyTrashed()->count();
        return view('admin.employees.index', compact('employees', 'archivedCount'));
    }

    public function archived()
    {
        $employees = Employee::onlyTrashed()->with('userAccount')->paginate(15);
        return view('admin.employees.archived', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Personal info first
            'emp_fn'          => ['required', 'string', 'max:100'],
            'emp_mn'          => ['nullable', 'string', 'max:100'],
            'emp_ln'          => ['required', 'string', 'max:100'],
            'emp_gender'      => ['required', 'in:male,female,other'],
            'emp_birthdate'   => ['required', 'date'],
            'emp_address'     => ['required', 'string'],
            'emp_contact_num' => ['required', 'string', 'max:30'],
            'emp_email'       => ['required', 'email', 'max:255', 'unique:employees,emp_email'],
            'emp_hiredate'    => ['required', 'date'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // Account info
            'username'        => ['required', 'string', 'max:100', 'unique:user_accounts,username'],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'role'            => ['required', 'in:admin,agent'],
        ]);

        DB::transaction(function () use ($request) {
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
        });

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show($id)
    {
        $employee = Employee::with('userAccount')->findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::with('userAccount')->findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::with('userAccount')->findOrFail($id);

        $request->validate([
            'emp_fn'          => ['required', 'string', 'max:100'],
            'emp_mn'          => ['nullable', 'string', 'max:100'],
            'emp_ln'          => ['required', 'string', 'max:100'],
            'emp_gender'      => ['required', 'in:male,female,other'],
            'emp_birthdate'   => ['required', 'date'],
            'emp_address'     => ['required', 'string'],
            'emp_contact_num' => ['required', 'string', 'max:30'],
            'emp_email'       => ['required', 'email', 'max:255', 'unique:employees,emp_email,' . $employee->id],
            'emp_hiredate'    => ['required', 'date'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'username'        => ['required', 'string', 'max:100', 'unique:user_accounts,username,' . ($employee->userAccount->id ?? 'NULL')],
            'password'        => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'            => ['required', 'in:admin,agent'],
        ]);

        DB::transaction(function () use ($request, $employee) {
            $employee->update([
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

            if ($employee->userAccount) {
                $employee->userAccount->update([
                    'username' => $request->username,
                    'role'     => $request->role,
                ]);
                if ($request->filled('password')) {
                    $employee->userAccount->update(['password' => Hash::make($request->password)]);
                }
            }
        });

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        DB::transaction(function () use ($employee) {
            $employee->delete(); // soft delete
        });

        return redirect()->route('admin.employees.index')->with('success', 'Employee archived successfully.');
    }

    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($employee) {
            $employee->restore();
        });

        return redirect()->route('admin.employees.archived')->with('success', 'Employee restored successfully.');
    }
}
