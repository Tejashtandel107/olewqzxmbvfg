<?php

namespace App\Listeners;

use App\Events\CompanyDeleted;
#use Illuminate\Contracts\Queue\ShouldQueue;
#use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\CompanyDeletedNotification;
use App\Models\AdminProfile;
use App\Models\User;

class SendCompanyDeletedNotification
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
     * @param  \App\Events\CompanyDeleted  $event
     * @return void
     */
    public function handle(CompanyDeleted $event)
    {
        //Send notification to admins
        if(isset($event->auth_user) && !$event->auth_user->isSuperAdmin()){
            $notifiers = User::whereHasMorph('profile',AdminProfile::class,function (Builder $query) {
                                    $query->where('notify_on_company_delete', 1);
                                }
                            )->get();

            Notification::send($notifiers, new CompanyDeletedNotification($event->company,$event->auth_user));
        }
    }
}
