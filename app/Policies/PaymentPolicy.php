<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\UserAccount;

class PaymentPolicy
{
    public function viewAny(UserAccount $user): bool
    {
        return in_array($user->role, ['admin', 'agent', 'customer']);
    }

    public function view(UserAccount $user, Payment $payment): bool
    {
        if (in_array($user->role, ['admin', 'agent'])) {
            return true;
        }

        if ($user->role === 'customer') {
            return $payment->booking->customer_id === $user->customer->id;
        }

        return false;
    }

    public function create(UserAccount $user): bool
    {
        return $user->role === 'customer';
    }

    public function verify(UserAccount $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'agent']);
    }

    public function reject(UserAccount $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'agent']);
    }
}
