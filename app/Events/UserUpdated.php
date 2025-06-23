<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
Use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $auth_user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->auth_user = Auth::user();
    }
}
