<?php


namespace Velstack\Pusher\NotificationDriver;

use Velstack\Pusher\Traits\Campaign;
use Velstack\Pusher\SMS;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PusherChannel
{
    use Campaign;

    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'routeNotificationForPusher')) {
            $phone = $notifiable->routeNotificationForPusher($notifiable);
        } else {
            $phone = $notifiable->phone;
        }

        $message = $notification->toPusher($notifiable);

       // $notifier = new SMS();
        $response = SMS::sendQuickSMS(['recipient'=>$phone, 'message'=> $message->content]);
        Log::info("Pusher response => $response");
        return $response;
    }

}
