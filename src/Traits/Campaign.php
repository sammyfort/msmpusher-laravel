<?php


namespace Velstack\Pusher\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Velstack\Pusher\NotificationDriver\PusherMessage;
use Velstack\Pusher\SMS;



trait Campaign
{
    use Requests;

    public static function sendQuick($recipients, $message=null){
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
        $object = User::findOrFail(auth()->id());
        if (method_exists($object, 'setPhoneColumnForSMS')) {
            $phone = $object->setPhoneColumnForSMS($object);
        } else {
            $phone = $object->phone;
        }
        $data = [
            "privatekey" => SMS::privateKey(),
            "publickey" => SMS::publicKey(),
            "sender" => SMS::senderId(),
            "numbers" =>   $phone,
            "message" =>   $message
        ];
        $pusher = self::postRequest($data);
        return $pusher;
    }

}
