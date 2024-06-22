<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $basic  = new \Vonage\Client\Credentials\Basic("ac42e63e", "muW2swq0QOWIoeH2");
        $client = new \Vonage\Client($basic);

        $OTP_CODE = 123;
        $phone_number = $request->phone;
        $response = $client->sms()->send(

            // phone number
            new \Vonage\SMS\Message\SMS("84339332612", 'Nhà thuốc WellCare', 'Mã OTP: '.$OTP_CODE.'')
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }
    }
}
