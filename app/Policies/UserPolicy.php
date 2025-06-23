<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        if($user->user_id === $model->user_id or $user->user_id === $model->created_by){
            return true;
        }
        elseif($model->isClient()){
            return $this->has_client_access($user,$model);
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return ($user->isAccountManager());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        if($user->user_id === $model->user_id or $user->user_id === $model->created_by){
            return true;
        }
        elseif($model->isClient()){
            return $this->has_client_access($user,$model);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        if($user->user_id === $model->user_id or $user->user_id === $model->created_by){
            return true;
        }
        elseif($model->isClient()){
            return $this->has_client_access($user,$model);
        }
        return false;
    }

    private function has_client_access(User $user, User $model){
        return ($user->assignedClients()->where("user_id",$model->user_id)->count()>0) ? true : false;
    }
}
