
## LARAVEL msmPusher PACKAGE

This package allows you to send SMS notifications using [MSMPusher](https://msmpusher.com/) APIs very easy in laravel projects.

## Installation

install with composer

```bash
  composer require velstack/pusher
```

## Configuration


The installation will create a  `'/config/velstack.php'` file. However, if doesn't you can run the command below.

```bash
php artisan vendor:publish --tag=velstack
```

Then get your api keys from your [msmpusher.com](https://msmpusher.com/) client area and set these keys in your velstack.php file

```php
return  [

'PUSHER_PRIVATE_KEY'=>  '****your_private_key',

'PUSHER_PUBLIC_KEY'=> '****your_public_key',

'PUSHER_SENDER_ID'=> '****senderID'

//make sure PUSHER_SENDER_ID value is already set on your msmpusher.com dashboard

];

```
Register the service provider in `'/config/app.php'` providers array

```php
'providers' => [

   Velstack\Pusher\VelstackServiceProvider::class,
];

 
 
```

## Send a quick SMS

```php

use App\Http\Controllers\Controller;
use App\Models\User;
use Velstack\Pusher\SMS;
 

class UserController extends  Controller{

  // sending a quick sms
  
  public function send()
  {
    SMS::sendQuick('233205550368', 'Your payment has been confirmed !');
    return 'Message sent successfully';
  }
  
  // to multiple numbers 
  public function toMany()
  {
    SMS::sendQuick('23320*******, 23320*******',  'This message is sent from the MSMPUSHER API. hurray!');
    return 'Message sent';
  }
  
    // OR
  
   public function fromDatabase()
   {
     $users =  User::pluck('phone');
     foreach ($users as $user)
     SMS::sendQuick($user, 'Good afternoon all users !');
     return 'Message has been to all users successfully';
   }
  
  
  
  /** you can also call the 'notify'. 
 * This approach will send the message to the authenticated user in your application.
 * So you don't need to pass a recipient. This must only be called on App\Models\User model.
 * NOTE: your Table must contain a 'phone' column. If doesn't, you may set a 'setPhoneColumnForSMS()'
 * method in your User::class model to return the custom column where you store phone numbers. eg.@after 
 * 
 * public function setPhoneColumnForSMS(){
     return $this->getAttributeValue('some_phone_column');
    }
 **/
 
 public function toAuthUser()
 {
   SMS::notify('Your subscription is expiring in 3 days.');
   return 'Message sent successfully';
 }
  
  
    // you can also call it like this
  
  public function welcomeMessage()
  {
    $sender = new SMS();
    $sender->sendQuick( '233205550368',   'Thank you for registering on our website !');
   
    #OR
    $sender = new SMS();
    $sender->notify('Thank you for registering on our website !');
    
    return 'Message sent successfully';
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
        "detail": "All Messages were sent successfully"
        
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
        return (new PusherMessage())->message("Dear $notifiable->firstname, Welcome to Velstack !.");

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
use Illuminate\Support\Facades\Notification;
 

class NotificationController extends  Controller{

  // sending notification
  
  public function sendNotification()
  {
    $user = User::find(1);
    $user->notify(new WelcomeNotification);
    return 'Message sent successfully';
  }
  
  
//  Using this notification channel, you must have a 'phone' column on the model fillable.
//  If your target model doesn't have a 'phone' column, set a setPhoneColumnForSMS() method 
//  in that model and specify the column where you store phone numbers like below;
  
}
 
```

#### In your model:

Make sure your target model uses the `Notifiable` Trait

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
    
    public function setPhoneColumnForSMS(){
     return $this->getAttributeValue('some_phone_column');
    }


     
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     
} 
```

#### sending on demand notification;

```php

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
 

class NotificationController extends  Controller{

  // sending notification
  
  
//  Sometimes you may need to send a notification to someone who 
//  is not stored as a "user" of your application. Using the 
//  Notification facade's route method, you may specify 'pusher' 
//  notification driver followed by the recipient before sending the notification.
  
  
  public function onDemandNotification()
  { 
    Notification::route('pusher', '020***0368')->notify(new WelcomeNotification());
     return 'Message sent successfully';
  }
  
}
 
```

#### status codes
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

```json
 {
    "type": "Not All Messages were sent successfully due to insufficient balance",
    "code": 1001,
    "error": "Insufficient balance",
    "detail": null
        
    }

```

```json
 {
    "type": "Missing API Parameters",
    "code": 1002,
    "error": "Missing API Parameters",
    "detail": "Incorrect private_key or public_key, or message data parameter"
        
    }

```

```json
 {
    "type": "Insufficient balance",
    "code": 1003,
    "error": "Insufficient balance",
    "detail": "Your Unit is insufficient to send messages. Please Top Up Your MSMPUSHER Digital Wallet"
        
    }

```


```json
 {
    "type": "Mismatched API key",
    "code": 1004,
    "error": "Mismatched API key",
    "detail": "Incorrect api key"
        
    }

```

```json
 {
    "type": "invalid_phone_number",
    "code": 1005,
    "error": "Invalid phone number",
    "detail": null
    }

```


```json
 {
    "type": "invalid Sender ID",
    "code": 1006,
    "error": "invalid Sender ID",
    "detail": "Sender ID must not be more than 11 Characters. Characters include white space."
    }

```

```json
 {
    "type": "Message scheduled for later delivery",
    "code": 1007,
    "error": "Message scheduled for later delivery",
    "detail": null
    }

```


```json
 {
    "type": "Empty Message",
    "code": 1008,
    "error": "Message string is empty",
    "detail": null
    }

```

```json
 {
    "type": "SMS sending failed",
    "code": 1009,
    "error": "SMS sending failed",
    "detail": null
    }

```

```json
 {
    "type": "No messages has been sent on the specified dates using the specified api key",
    "code": 1010,
    "error": "No messages has been sent on the specified dates using the specified api key",
    "detail": null
    }

```
