<?php

namespace Velstack\Pusher;

use Velstack\Pusher\NotificationDriver\PusherChannel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class VelstackServiceProvider extends ServiceProvider
{


    public function register()
    {
        Notification::extend('pusher', function ($app) {
            return new PusherChannel();
        });


    }


    public function boot()
    {

        $this->mergeConfigFrom( __DIR__.'/config/velstack.php', 'velstack');

        $this->publishes([
            __DIR__.'/config/velstack.php' => config_path('velstack.php'),
        ], 'velstack');
    }

}
