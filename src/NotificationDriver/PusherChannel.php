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
        if (method_exists($notifiable, 'setNotificationMedium')) {
            $phone = $notifiable->setNotificationMedium($notifiable);
        } else {
            $phone = $notifiable->phone;
        }

        if (is_null($phone)){
           return  response()->json('Destination phone is empty', 400);
        }

        $message = $notification->toPusher($notifiable);

        // $notifier = new SMS();
        try {
            $response = SMS::sendQuick($phone, $message->content());
            return $response;
        }catch (\Exception $exception){
            Log::info("Pusher response => $exception");
            throw $exception;
        }
    }

}
