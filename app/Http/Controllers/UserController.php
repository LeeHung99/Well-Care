<?php

namespace App\Http\Controllers;

use Infobip\Api\SmsApi;
use Infobip\ApiException;
use Infobip\Configuration;
use App\Services\SpeedSMSService;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

// use to;
// use Infobip\Api\SmsApi;

// use Infobip\ApiException;
// use Infobip\Configuration;
// use App\Services\SmsService;
// use Illuminate\Http\Request;
// use App\Services\VonageService;
// use App\Services\SpeedSMSService;
// use Infobip\Model\SmsDestination;

// use Infobip\Model\SmsTextualMessage;
// use Infobip\Model\SmsAdvancedTextualRequest;
// use Illuminate\Notifications\Messages\VonageMessage;

class UserController extends Controller
{
    protected $speedSMSService;

    public function __construct(SpeedSMSService $speedSMSService)
    {
        $this->speedSMSService = $speedSMSService;
    }

    public function send()
    {
        // $phoneNumber = '84962925412' ;
        // $message = 'test OTP';

        // $response = $this->speedSMSService->sendSMS($phoneNumber, $message);

        // return response()->json($response);

        $configuration = new Configuration(
            host: 'ggx3xj.api.infobip.com',
            apiKey: '5144fbeb4470c78e61d0749aaaade38b-d349ec87-92ee-44f6-9cfc-50ab48c57064'
        );

        $sendSmsApi = new SmsApi(config: $configuration);

        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: '+84866808370')
            ],
            from: 'test OTP',
            text: '500k bao phong'
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);
            return 'Gửi thành công';
        } catch (ApiException $apiException) {
            return 'Lỗi' . $apiException->getMessage();;
        }

        // $sendSmsApi = new SmsApi(config: $configuration);

        // $message = new SmsTextualMessage(
        //     destinations: [
        //         new SmsDestination(to: '41793026727')
        //     ],
        //     from: 'InfoSMS',
        //     text: 'This is a dummy SMS message sent using infobip-api-php-client'
        // );

        // $request = new SmsAdvancedTextualRequest(messages: [$message]);

        // try {
        //     $smsResponse = $sendSmsApi->sendSmsMessage($request);
        // } catch (ApiException $apiException) {
        //     // HANDLE THE EXCEPTION
        // }
    }
}
