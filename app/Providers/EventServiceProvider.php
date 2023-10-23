<?php

namespace App\Providers;

use App\Events\AssignedReviewer;
use App\Events\NotificationAdminTimeOffEvent;
use App\Events\NotificationEmployeTimeOffEvent;
use App\Events\ShareDocEvent;
use App\Listeners\NotificationAdminTimeOffListener;
use App\Listeners\NotificationEmployeTimeOffListener;
use App\Listeners\SendAssignedReviewerNotification;
use App\Listeners\ShareDocListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AssignedReviewer::class => [
            SendAssignedReviewerNotification::class,
        ],

        ShareDocEvent::class => [
            ShareDocListener::class
        ],

        NotificationEmployeTimeOffEvent::class => [
            NotificationEmployeTimeOffListener::class
        ],

        NotificationAdminTimeOffEvent::class => [
            NotificationAdminTimeOffListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
