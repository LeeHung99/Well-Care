<?php

namespace App\Http\Controllers;

use App\Models\User;
use Infobip\Api\SmsApi;
use Infobip\ApiException;
use Infobip\Configuration;
use Illuminate\Http\Request;
use App\Services\SpeedSMSService;
use Infobip\Model\SmsDestination;
use Illuminate\Support\Facades\DB;
use Infobip\Model\SmsTextualMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

use Infobip\Model\SmsAdvancedTextualRequest;

class UserController extends Controller
{
    protected $speedSMSService;

    public function __construct(SpeedSMSService $speedSMSService)
    {
        $this->speedSMSService = $speedSMSService;
    }

    public function send(Request $request)
    {
        $configuration = new Configuration(
            host: 'ggx3xj.api.infobip.com',
            apiKey: '5144fbeb4470c78e61d0749aaaade38b-d349ec87-92ee-44f6-9cfc-50ab48c57064'
        );

        // dd($request->input('phone'));
        $brand_name = 'WellCare';
        $sendSmsApi = new SmsApi(config: $configuration);

        $request->validate([
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);



        // $phoneNumber = $request->input('phone');
        $phoneNumber = '0339332612';
        $userExists = User::where('phone', $phoneNumber)->exists();
        $otp = random_int(100000, 999999);
        $expiration = now()->addSecond(30);
        // Cache::store('array')->put('otp_' .$phoneNumber, [
        //     'code' => $otp,
        //     'expiration' => $expiration,
        //     'user_exists' => $userExists
        // ], $expiration);

        session(['phoneNumber' => $phoneNumber]);
        session([
            'otp_' . $phoneNumber => [
                'code' => $otp,
                'expiration' => $expiration,
                'user_exists' => $userExists
            ]
        ]);
        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: '+84' . ltrim($phoneNumber, '0')) // $phoneNumber request phone user
            ],
            from: $brand_name,
            text: "Mã OTP của bạn là: $otp. Mã có hiệu lực trong 30 giây."
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);
            return response()->json(['message' => 'Gửi OTP thành công', 'user_exists' => $userExists], 200);
            // return 'gửi thành công';
        } catch (ApiException $apiException) {
            return response()->json(['error' => 'Lỗi gửi OTP: ' . $apiException->getMessage()], 500);
            // return 'Lỗi' . $apiException->getMessage();;
        }
    }
    public function verify(Request $request)
    {
        $request->validate([
            // 'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'otp' => 'required|digits:6',
        ]);
        // $phoneNumber = session('phoneNumber');
        $phoneNumber = $request->phone;
        $inputOtp = $request->otp;


        // $sessionData = session('otp_' . $phoneNumber);
        // $storedOtp = $sessionData['code'];
        // $expiration = $sessionData['expiration'];
        // $userExists = $sessionData['user_exists'];
        // if (!$sessionData) {
        //     return response()->json(['error' => 'OTP không tồn tại hoặc đã hết hạn'], 400);
        // }


        $expiration = now()->addSecond(30);
        

        // $cachedData = Cache::get('otp_' . $phoneNumber);
        // if (!Cache::has('otp_' . $phoneNumber)) {
        //     return response()->json(['error' => 'OTP không tồn tại hoặc đã hết hạn'], 400);
        // }



        // session test 
        session([
            'otp_' . $phoneNumber => [
                'code' => 123456,
                'expiration' => $expiration,
                'user_exists' => false
            ]
        ]);
        $sessionData = session('otp_' . $phoneNumber);
        $userExists = $sessionData['user_exists'];
        if (now()->isAfter($expiration)) {
            session()->forget('otp_' . $phoneNumber);
            return response()->json(['error' => 'OTP đã hết hạn'], 400);
        }

        if ($inputOtp != 123456) { // $inputOtp != session('otp'. $phoneNumber['code'])
            return response()->json(['error' => 'OTP không chính xác'], 400);
        }

        session()->forget('otp_' . $phoneNumber);

        if ($userExists) {
            $user = User::where('phone', $phoneNumber)->first();
            return response()->json([
                'message' => 'Đăng nhập thành công',
                'user' => $user
            ], 200);
        } else {
            $user = User::create([
                'name' => $phoneNumber,
                'phone' => $phoneNumber,
                'password' => Hash::make($inputOtp),
            ]);
            return response()->json([
                'message' => 'Đăng nhập thành công',
                'user' => $user
            ], 201);
        }
        // return response()->json(['message' => 'Xác thực OTP thành công'], 200);
    }
}
