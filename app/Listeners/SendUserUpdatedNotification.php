<?php

namespace App\Listeners;

use App\Events\UserUpdated;
#use Illuminate\Contracts\Queue\ShouldQueue;
#use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\ClientUpdatedNotification;
use App\Models\AdminProfile;
use App\Models\User;

class SendUserUpdatedNotification
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
    public function handle(UserUpdated $event)
    {
        //Send notification to admins
        if(isset($event->auth_user) && !$event->auth_user->isSuperAdmin()){
            if($event->user->isClient()){
                $notifiers = User::whereHasMorph('profile',AdminProfile::class,function (Builder $query) {
                                        $query->where('notify_on_client_update', 1);
                                    }
                                )->get();

                Notification::send($notifiers, new ClientUpdatedNotification($event->user,$event->auth_user));
            }
        }
    }
}
