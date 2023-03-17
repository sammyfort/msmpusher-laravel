
## LARAVEL msmPusher PACKAGE

This package allows you to send SMS notifications using msmPusher.com APIs very easy in laravel projects.

## Installation

install with composer

```bash
  composer require velstack/pusher
```

## Usage/Examples

The installation will create a  __DIR__.'/config/velstack.php' file. However, if doesn't you can run the command below.

```bash
php artisan vendor:publish --tag=velstack
```

Then get your api keys from your msmpusher.com client area and set these keys in your velstack.php file

```php
return  [

'VELSTACK_PUSHER_PRIVATE'=>  '****your_private_key',

'VELSTACK_PUSHER_PUBLIC'=> '****your_public_key',

'VELSTACK_PUSHER_SENDER_ID'=> '****senderID'

//make sure VELSTACK_PUSHER_SENDER_ID value is already set on your msmpusher.com dashboard

];

```

#### Send a quick SMS

```php

use App\Http\Controllers\Controller;
use Velstack\Pusher\SMS;
use Illuminate\Http\Request;

class UserController extends  Controller{

  // sending sms statically
  
  public function send(){
  SMS::sendQuickSMS(['recipient'=> '233205550368', 'message'=> 'Your payment has been confirmed !']);
  }
  
  
  /** you can also use this approach. This approach will send the message to the authenticated user in your database.
 *  so you don't need to pass a recipient to the array. 
 * NOTE: yours Users Table must contain a 'phone' column.
 **/
 
 public function toAuthUser(){
  SMS::notify(['message'=> 'Your subscription is expiring in 3 days.']);
  }
  
  
    // you can also call it like this
  
  public function welcomeMessage(){
   $sender = new SMS();
   $sender->sendQuickSMS(['recipient'=> '233205550368', 'message'=> 'Thank you for registering on our website !']);
   
   #OR
   $sender = new SMS();
   $sender->notify(['message'=> 'Thank you for registering on our website !']);
   
  }
  
}
 
```
