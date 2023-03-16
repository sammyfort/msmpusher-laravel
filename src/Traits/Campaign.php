<?php


namespace Velstack\Pusher\Traits;


use Illuminate\Support\Facades\Http;


trait Campaign
{

    public function sendQuickSMS(array $recipient, $message=null){
        $data = [
            "privatekey" => $this->private,
            "publickey" => $this->public,
            "sender" => $this->sender,
            "numbers" =>  $recipient,
            "message" =>   $message ?: $this->message
        ];

        $pusher = Http::withHeaders([
            "Content-Type" =>  "application/json",
            "Accept: application/json"
        ])->post($this->quickSMSURL,  $data);

        return $pusher;
    }

    public function pushToAuth(array $message=null){
        $data = [
            "privatekey" => $this->private,
            "publickey" => $this->public,
            "sender" => $this->sender,
            "numbers" =>  auth()->user()->phone,
            "message" =>   $message ?: $this->message
        ];

        $pusher = Http::withHeaders([
            "Content-Type" =>  "application/json",
            "Accept: application/json"
        ])->post($this->quickSMSURL,  $data);

        return $pusher;
    }
}
