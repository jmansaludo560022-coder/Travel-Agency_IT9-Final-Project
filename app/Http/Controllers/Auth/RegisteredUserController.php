<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username'  => ['required', 'string', 'max:100', 'unique:user_accounts,username'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'cus_fn'    => ['required', 'string', 'max:100'],
            'cus_mn'    => ['nullable', 'string', 'max:100'],
            'cus_ln'    => ['required', 'string', 'max:100'],
            'cus_email' => ['required', 'email', 'max:255', 'unique:customers,cus_email'],
            'phone_num' => ['required', 'string', 'max:30'],
        ]);

        $userAccount = DB::transaction(function () use ($request) {
            // 1. Create Customer profile first
            $customer = Customer::create([
                'cus_fn'    => $request->cus_fn,
                'cus_mn'    => $request->cus_mn,
                'cus_ln'    => $request->cus_ln,
                'cus_email' => $request->cus_email,
                'phone_num' => $request->phone_num,
            ]);

            // 2. Create UserAccount with customer_id FK
            return UserAccount::create([
                'username'    => $request->username,
                'password'    => Hash::make($request->password),
                'role'        => 'customer',
                'employee_id' => null,
                'customer_id' => $customer->id,
            ]);
        });

        event(new Registered($userAccount));
        Auth::login($userAccount);

        return redirect(route('customer.dashboard'));
    }
}
