<?php

namespace App\Listeners;

use App\Events\UserDeleted;
#use Illuminate\Contracts\Queue\ShouldQueue;
#use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\ClientDeletedNotification;
use App\Models\AdminProfile;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendUserDeletedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserCreated  $event
     * @return void
     */
    public function handle(UserDeleted $event)
    {
        //Send notification to admins
        if(isset($event->auth_user) && !$event->auth_user->isSuperAdmin()){
            if($event->user->isClient()){
                $notifiers = User::whereHasMorph('profile',AdminProfile::class,function (Builder $query) {
                                        $query->where('notify_on_client_delete', 1);
                                    }
                                )->get();

                Notification::send($notifiers, new ClientDeletedNotification($event->user,$event->auth_user));
            }
        }
    }
}
