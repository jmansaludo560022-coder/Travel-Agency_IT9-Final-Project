<?php

namespace App\Policies;

use App\Models\TravelPackage;
use App\Models\UserAccount;

class PackagePolicy
{
    /**
     * Determine if the user can view any packages.
     */
    public function viewAny(UserAccount $user): bool
    {
        return in_array($user->role, ['admin', 'agent']);
    }

    /**
     * Determine if the user can view the package.
     */
    public function view(UserAccount $user, TravelPackage $package): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent') {
            return $package->employee_id === $user->employee->id;
        }

        return false;
    }

    /**
     * Determine if the user can create packages.
     */
    public function create(UserAccount $user): bool
    {
        return in_array($user->role, ['admin', 'agent']);
    }

    /**
     * Determine if the user can update the package.
     */
    public function update(UserAccount $user, TravelPackage $package): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent') {
            return $package->employee_id === $user->employee->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the package.
     */
    public function delete(UserAccount $user, TravelPackage $package): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent') {
            return $package->employee_id === $user->employee->id;
        }

        return false;
    }

    /**
     * Determine if the user can toggle visibility of the package.
     */
    public function toggleVisibility(UserAccount $user, TravelPackage $package): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent') {
            return $package->employee_id === $user->employee->id;
        }

        return false;
    }
}
