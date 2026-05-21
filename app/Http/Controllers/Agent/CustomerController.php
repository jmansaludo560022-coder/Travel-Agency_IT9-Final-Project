<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Review;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['userAccount', 'bookings'])
            ->withCount('bookings')
            ->paginate(15);

        return view('agent.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('agent.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cus_fn'    => ['required', 'string', 'max:100'],
            'cus_mn'    => ['nullable', 'string', 'max:100'],
            'cus_ln'    => ['required', 'string', 'max:100'],
            'cus_email' => ['required', 'email', 'max:255', 'unique:customers,cus_email'],
            'phone_num' => ['required', 'string', 'max:30'],
            'username'  => ['required', 'string', 'max:100', 'unique:user_accounts,username'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create Customer profile
            $customer = Customer::create([
                'cus_fn'    => $request->cus_fn,
                'cus_mn'    => $request->cus_mn,
                'cus_ln'    => $request->cus_ln,
                'cus_email' => $request->cus_email,
                'phone_num' => $request->phone_num,
            ]);

            // 2. Create UserAccount linked to customer
            UserAccount::create([
                'username'    => $request->username,
                'password'    => Hash::make($request->password),
                'role'        => 'customer',
                'customer_id' => $customer->id,
                'employee_id' => null,
            ]);
        });

        return redirect()->route('agent.customers.index')
            ->with('success', 'Customer account created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::with(['userAccount', 'bookings.travelPackage', 'reviews'])
            ->findOrFail($id);

        return view('agent.customers.show', compact('customer'));
    }

    public function replyReview(Request $request, Review $review)
    {
        $this->authorize('reply', $review);

        $request->validate([
            'review_reply' => ['required', 'string', 'min:5'],
        ]);

        DB::transaction(function () use ($request, $review) {
            $review->update(['review_reply' => $request->review_reply]);
        });

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }
}
