<?php


namespace Velstack\Pusher;
use Velstack\Pusher\Traits\Campaign;

class VelstackSMS
{
    use Campaign;

    private string $quickSMSURL = 'https://api.msmpusher.net/v1/velstack';


    private string $apiKey;
    private string $private;
    private string $public;
    private string $sender;
    private string $message;
    private string $isSchedule;
    private string $scheduleDate;



    public function __construct()
    {
        $this->private = config('velstack.VELSTACK_PUSHER_PRIVATE');
        $this->public = config('velstack.VELSTACK_PUSHER_PUBLIC');
        $this->sender = config('velstack.VELSTACK_PUSHER_SENDER_ID');
        $this->isSchedule = false;
    }

}
