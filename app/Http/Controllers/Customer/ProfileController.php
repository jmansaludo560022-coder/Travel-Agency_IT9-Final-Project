<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit()
    {
        $user     = auth()->user();
        $customer = $user->customer;

        return view('customer.profile.edit', compact('user', 'customer'));
    }

    public function update(Request $request)
    {
        $user     = auth()->user();
        $customer = $user->customer;

        $request->validate([
            // Personal info
            'cus_fn'    => ['required', 'string', 'max:100'],
            'cus_mn'    => ['nullable', 'string', 'max:100'],
            'cus_ln'    => ['required', 'string', 'max:100'],
            'cus_email' => ['required', 'email', 'max:255', Rule::unique('customers', 'cus_email')->ignore($customer->id)],
            'phone_num' => ['required', 'string', 'max:30'],
            // Account
            'username'  => ['required', 'string', 'max:100', Rule::unique('user_accounts', 'username')->ignore($user->id)],
            'password'  => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request, $user, $customer) {
            // Update customer profile
            $customer->update([
                'cus_fn'    => $request->cus_fn,
                'cus_mn'    => $request->cus_mn,
                'cus_ln'    => $request->cus_ln,
                'cus_email' => $request->cus_email,
                'phone_num' => $request->phone_num,
            ]);

            // Update username
            $user->update(['username' => $request->username]);

            // Update password only if provided
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }
        });

        return redirect()->route('customer.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
