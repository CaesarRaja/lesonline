<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $currentUser, User $targetUser): bool
    {
        return $currentUser->isAdmin();
    }

    public function delete(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        if ($targetUser->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return false;
        }

        return $currentUser->isAdmin();
    }
}
