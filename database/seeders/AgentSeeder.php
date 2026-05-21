<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        // Agent 1 — create Employee first, then UserAccount with FK
        $employee1 = Employee::create([
            'emp_fn' => 'John',
            'emp_mn' => 'A',
            'emp_ln' => 'Smith',
            'emp_gender' => 'male',
            'emp_birthdate' => '1985-05-15',
            'emp_address' => '456 Agent Avenue',
            'emp_contact_num' => '+1234567891',
            'emp_email' => 'john.smith@tourpackage.com',
            'emp_hiredate' => '2021-03-15',
            'commission_rate' => 0.0500,
        ]);

        UserAccount::create([
            'username' => 'agent1',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'employee_id' => $employee1->id,
            'customer_id' => null,
        ]);

        // Agent 2
        $employee2 = Employee::create([
            'emp_fn' => 'Maria',
            'emp_mn' => 'B',
            'emp_ln' => 'Garcia',
            'emp_gender' => 'female',
            'emp_birthdate' => '1992-08-20',
            'emp_address' => '789 Travel Road',
            'emp_contact_num' => '+1234567892',
            'emp_email' => 'maria.garcia@tourpackage.com',
            'emp_hiredate' => '2022-01-10',
            'commission_rate' => 0.0450,
        ]);

        UserAccount::create([
            'username' => 'agent2',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'employee_id' => $employee2->id,
            'customer_id' => null,
        ]);
    }
}
