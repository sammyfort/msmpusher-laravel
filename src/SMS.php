<?php


namespace Velstack\Pusher;
use Velstack\Pusher\Traits\Campaign;

class SMS
{
    use Campaign;


    private static function endPoint(): string{
        return 'https://api.msmpusher.net/v1/velstack';
    }

    private static  function privateKey(): string{
        return config('velstack.VELSTACK_PUSHER_PRIVATE') ?? response()->json('error', 500);
    }

    private static  function publicKey(): string{
        return  config('velstack.VELSTACK_PUSHER_PUBLIC') ?? response()->json('error', 500);
    }

    private static function senderId(): string{
        return config('velstack.VELSTACK_PUSHER_SENDER_ID') ?? response()->json('error', 500);
    }

}
