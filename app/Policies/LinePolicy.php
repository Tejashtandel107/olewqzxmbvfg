<?php

namespace App\Policies;

use App\Models\Line;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }    

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Line  $line
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Line $line)
    {
        return ($user->user_id === $line->operator_id or $user->user_id === $line->client_id or $user->user_id === $line->account_manager_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return ($user->isAccountManager() || $user->isOperator());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Line  $line
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Line $line)
    {
        return ($user->user_id === $line->operator_id or $user->user_id === $line->client_id or $user->user_id === $line->account_manager_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Line  $line
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Line $line)
    {
        return ($user->user_id === $line->operator_id or $user->user_id === $line->client_id or $user->user_id === $line->account_manager_id);
    }
}
