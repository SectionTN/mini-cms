<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can change the role of the model.
     */
    public function updateRole(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->isNot($model);
    }
}
