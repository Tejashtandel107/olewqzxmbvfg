<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserDeleted;
use App\Events\CompanyCreated;
use App\Events\CompanyUpdated;
use App\Events\CompanyDeleted;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreated::class => [
            \App\Listeners\SendUserCreatedNotification::class,
        ],
        UserUpdated::class => [
            \App\Listeners\SendUserUpdatedNotification::class,
        ],
        UserDeleted::class => [
            \App\Listeners\SendUserDeletedNotification::class,
        ],
        CompanyCreated::class => [
            \App\Listeners\SendCompanyCreatedNotification::class,
        ],
        CompanyUpdated::class => [
            \App\Listeners\SendCompanyUpdatedNotification::class,
        ],
        CompanyDeleted::class => [
            \App\Listeners\SendCompanyDeletedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
