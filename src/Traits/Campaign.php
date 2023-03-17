<?php


namespace Velstack\Pusher\Traits;

use Velstack\Pusher\SMS;



trait Campaign
{
    use Requests;

    public static function sendQuickSMS($data){
        $data = [
            "privatekey" => SMS::privateKey(),
            "publickey" => SMS::publicKey(),
            "sender" => SMS::senderId(),
            "numbers" =>  $data['recipient'],
            "message" =>   $data['message']
        ];
        $pusher = self::postRequest($data);
        return $pusher;
    }

    public static function notify($data){
        $data = [
            "privatekey" => SMS::privateKey(),
            "publickey" => SMS::publicKey(),
            "sender" => SMS::senderId(),
            "numbers" =>  auth()->user()->phone,
            "message" =>   $data['message']
        ];
        $pusher = self::postRequest($data);
        return $pusher;
    }






}
