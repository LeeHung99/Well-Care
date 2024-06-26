<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Services\VonageService;
use App\Services\SpeedSMSService;
class UserController extends Controller
{
    protected $speedSMSService;

    public function __construct(SpeedSMSService $speedSMSService)
    {
        $this->speedSMSService = $speedSMSService;
    }

    public function send(Request $request)
    {
        $phoneNumber = '84962925412' ;
        $message = 'test OTP';

        $response = $this->speedSMSService->sendSMS($phoneNumber, $message);

        return response()->json($response);
    }
}
