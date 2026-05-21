<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Employee first (no FK dependency)
        $employee = Employee::create([
            'emp_fn' => 'Admin',
            'emp_mn' => null,
            'emp_ln' => 'User',
            'emp_gender' => 'male',
            'emp_birthdate' => '1990-01-01',
            'emp_address' => '123 Admin Street',
            'emp_contact_num' => '+1234567890',
            'emp_email' => 'admin@tourpackage.com',
            'emp_hiredate' => '2020-01-01',
            'commission_rate' => 0.0000,
        ]);

        // 2. Create UserAccount with employee_id FK
        UserAccount::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => $employee->id,
            'customer_id' => null,
        ]);
    }
}
