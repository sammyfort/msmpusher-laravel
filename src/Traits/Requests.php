<?php


namespace Velstack\Pusher\Traits;


use Illuminate\Support\Facades\Http;
use Velstack\Pusher\SMS;

trait Requests
{

    public static function postRequest($data=null){

        $pusher = Http::withHeaders([
            "Content-Type" =>  "application/json",
            "Accept: application/json",
            "Cache-Control" => "no-cache"
        ])->post(SMS::endPoint(),  $data);
        $pusher->throw();
        if ($pusher['status'] != 1000){
            return  response()->json(json_decode($pusher), 500);
        }
        return $pusher;
    }

}
