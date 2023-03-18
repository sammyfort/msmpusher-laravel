
## LARAVEL msmPusher PACKAGE

This package allows you to send SMS notifications using msmPusher.com APIs very easy in laravel projects.

## Installation

install with composer

```bash
  composer require velstack/pusher
```

## Usage/Examples


The installation will create a  `'/config/velstack.php'` file. However, if doesn't you can run the command below.

```bash
php artisan vendor:publish --tag=velstack
```

Then get your api keys from your msmpusher.com client area and set these keys in your velstack.php file

```php
return  [

'PUSHER_PRIVATE_KEY'=>  '****your_private_key',

'PUSHER_PUBLIC_KEY'=> '****your_public_key',

'PUSHER_SENDER_ID'=> '****senderID'

//make sure PUSHER_SENDER_ID value is already set on your msmpusher.com dashboard

];

```
Register the service provider in `'/config/app.php'` provider array

```php
'providers' => [

   Velstack\Pusher\VelstackServiceProvider::class,
];

'aliases'=> [

 'SMS'=>   Velstack\Pusher\Facades::class
];
 
```

#### Send a quick SMS

```php

use App\Http\Controllers\Controller;
use App\Models\User;
use Velstack\Pusher\SMS;
use Illuminate\Http\Request;

class UserController extends  Controller{

  // sending a quick sms
  
  public function send()
  {
    SMS::sendQuickSMS('233205550368', 'Your payment has been confirmed !');
  }
  
  // to multiple numbers 
  public function toMany()
  {
    SMS::sendQuickSMS('23320*******, 23320*******',  'Your payment has been confirmed !');
  }
  
    // OR
  
   public function fromDatabase()
   {
     $users =  User::pluck('phone');
     foreach ($users as $user)
     SMS::sendQuickSMS($user, 'Your payment has been confirmed !');
   }
  
  
  
  /** you can also call the 'notify'. This approach will send the message to the authenticated user in your application.
 *  so you don't need to pass a recipient. 
 * NOTE: yours Users Table must contain a 'phone' column.
 **/
 
 public function toAuthUser()
 {
   SMS::notify('Your subscription is expiring in 3 days.');
 }
  
  
    // you can also call it like this
  
  public function welcomeMessage()
  {
    $sender = new SMS();
    $sender->sendQuickSMS( '233205550368',   'Thank you for registering on our website !');
   
    #OR
    $sender = new SMS();
    $sender->notify('Thank you for registering on our website !');
  }
  
}
 
```
#### output
```json
 {
        "type": "Message(s) Sent",
        "status": 1000,
        "sms_id": "MSG_TRANS_1603120251942",
        "Receiver_numbers": "23320555038",
        "error": "Null",
        "detail": "All Messages was sent successfully"
        
    }

```


## Using the notification channel

#### In the notification class;


```php

namespace App\Notifications;

use Velstack\Pusher\NotificationDriver\PusherMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;


    public function __construct()
    {
        //
    }


    public function via($notifiable)
    {
        return ['pusher'];
    }


    public function toPusher($notifiable)
    {
        return (new PusherMessage())->message("Dear $notifiable->firstname, than you for showing up.");

    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

 
```

#### sending in your controller;

```php

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
 

class NotificationController extends  Controller{

  // sending notification
  
  public function sendNotification()
  {
    $user = User::find(1);
    $user->notify(new WelcomeNotification);
  }
  
}
 
```

###### NOTE:
* `Using the notification channel, you must have a 'phone' column on the target table.
  If your target table doesn't have a 'phone' column, set a setNotificationMedium() method in your model and specify the column like below;`
```php

namespace App\Models;

use Velstack\Pusher\NotificationDriver\PusherChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
 
 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

     
    protected $fillable = [
        'name',
        'email',
        'some_phone_column',
        'password',
    ];
    
    public function setNotificationMedium(){
     return auth()->user()->some_phone_column;
    }


    
   
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     
} 
```


