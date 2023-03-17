<?php


namespace Velstack\Pusher\Traits;

use Velstack\Pusher\NotificationDriver\PusherMessage;
use Velstack\Pusher\SMS;



trait Campaign
{
    use Requests;

    public static function sendQuickSMS($recipients, $message=null){
        $def = new PusherMessage();
        $data = [
            "privatekey" => SMS::privateKey(),
            "publickey" => SMS::publicKey(),
            "sender" => SMS::senderId(),
            "numbers" =>  $recipients,
            "message" =>   $message ?? $def->message($message)
        ];
        $pusher = self::postRequest($data);
        return $pusher;
    }

    public static function notify($message=null){
        $data = [
            "privatekey" => SMS::privateKey(),
            "publickey" => SMS::publicKey(),
            "sender" => SMS::senderId(),
            "numbers" =>  auth()->user()->phone,
            "message" =>   $message
        ];
        $pusher = self::postRequest($data);
        return $pusher;
    }






}
