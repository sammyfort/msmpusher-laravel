<?php

namespace Velstack\Pusher;

use Velstack\Pusher\NotificationDriver\PusherChannel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class VelstackServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Notification::extend('pusher', function ($app) {
            return new PusherChannel();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/config/velstack.php' => config_path('velstack.php'),
        ], 'velstack');
    }

}
