<?php


namespace Velstack\Pusher\NotificationDriver;

use Illuminate\Notifications\AnonymousNotifiable;
use Velstack\Pusher\Traits\Campaign;
use Velstack\Pusher\SMS;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PusherChannel
{
    use Campaign;

    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'setPhoneColumnForSMS'))
        {
            $phone = $notifiable->setPhoneColumnForSMS($notifiable);
        }
        elseif ($notifiable instanceof  AnonymousNotifiable)
        {
            $phone = $notifiable->routeNotificationFor('pusher');
        }
        else
        {
            $phone = $notifiable->phone;
        }

        if (is_null($phone)){
           return  response()->json('Destination phone is empty', 400);
        }

        $message = $notification->toPusher($notifiable);

        // $notifier = new VelstackSMS() => SMS::class;
        try {
            $response = SMS::sendQuick($phone, $message->content());
            return json_decode($response);
        }catch (\Exception $exception){
            Log::info("Pusher response => $exception");
            throw $exception;
        }
    }

}
