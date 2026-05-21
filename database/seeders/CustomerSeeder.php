<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Customer 1 — create Customer first, then UserAccount with FK
        $customer1 = Customer::create([
            'cus_fn' => 'Alice',
            'cus_mn' => 'M',
            'cus_ln' => 'Johnson',
            'cus_email' => 'alice.johnson@email.com',
            'phone_num' => '+1234567893',
        ]);

        UserAccount::create([
            'username' => 'customer1',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'employee_id' => null,
            'customer_id' => $customer1->id,
        ]);

        // Customer 2
        $customer2 = Customer::create([
            'cus_fn' => 'Bob',
            'cus_mn' => null,
            'cus_ln' => 'Williams',
            'cus_email' => 'bob.williams@email.com',
            'phone_num' => '+1234567894',
        ]);

        UserAccount::create([
            'username' => 'customer2',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'employee_id' => null,
            'customer_id' => $customer2->id,
        ]);

        // Customer 3
        $customer3 = Customer::create([
            'cus_fn' => 'Carol',
            'cus_mn' => 'L',
            'cus_ln' => 'Davis',
            'cus_email' => 'carol.davis@email.com',
            'phone_num' => '+1234567895',
        ]);

        UserAccount::create([
            'username' => 'customer3',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'employee_id' => null,
            'customer_id' => $customer3->id,
        ]);
    }
}
