<?php

namespace App\Listeners;

use App\Events\CompanyUpdated;
#use Illuminate\Contracts\Queue\ShouldQueue;
#use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\CompanyUpdatedNotification;
use App\Models\AdminProfile;
use App\Models\User;

class SendCompanyUpdatedNotification
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
     * @param  \App\Events\CompanyUpdated  $event
     * @return void
     */
    public function handle(CompanyUpdated $event)
    {
        //Send notification to admins
        if(isset($event->auth_user) && !$event->auth_user->isSuperAdmin()){
            $notifiers = User::whereHasMorph('profile',AdminProfile::class,function (Builder $query) {
                                    $query->where('notify_on_company_update', 1);
                                }
                            )->get();

            Notification::send($notifiers, new CompanyUpdatedNotification($event->company,$event->auth_user));
        }
    }
}
