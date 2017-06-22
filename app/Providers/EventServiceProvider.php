<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\TenderClose' => [
            'App\Listeners\TenderCloseNotification',
        ],
        'App\Events\NewUserActivate' => [
            'App\Listeners\UserActivateNotification',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\AdminEventSubscriber',
        'App\Listeners\ClientEventSubscriber',
        'App\Listeners\ProviderEventSubscriber',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
