<?php

namespace App\Policies;

use App\Models\Faq;
use App\Models\UserAccount;

class FaqPolicy
{
    public function viewAny(UserAccount $user): bool
    {
        return $user->role === 'admin';
    }

    public function create(UserAccount $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(UserAccount $user, Faq $faq): bool
    {
        return $user->role === 'admin';
    }

    public function delete(UserAccount $user, Faq $faq): bool
    {
        return $user->role === 'admin';
    }
}
