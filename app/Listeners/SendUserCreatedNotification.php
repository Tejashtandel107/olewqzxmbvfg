<?php

namespace App\Listeners;

use App\Events\UserCreated;
#use Illuminate\Contracts\Queue\ShouldQueue;
#use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\SendLoginCredentialsNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\ClientCreatedNotification;
use App\Models\AdminProfile;
use App\Models\User;
use Illuminate\Http\Request;


class SendUserCreatedNotification
{
    public $request;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        //Send notification to admins
        if(isset($event->auth_user) && !$event->auth_user->isSuperAdmin()){
            if($event->user->isClient()){
                $notifiers = User::whereHasMorph('profile',AdminProfile::class,function (Builder $query) {
                                        $query->where('notify_on_client_create', 1);
                                    }
                                )->get();

                Notification::send($notifiers, new ClientCreatedNotification($event->user,$event->auth_user));
            }
        }
        $event->user->notify(new SendLoginCredentialsNotification($event->user,$this->request));  
    }
}
