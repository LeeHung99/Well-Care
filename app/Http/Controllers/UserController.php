<?php

namespace App\Http\Controllers;

use App\Models\User;
use Infobip\Api\SmsApi;
use Infobip\ApiException;
use Infobip\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\SpeedSMSService;
use Infobip\Model\SmsDestination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Infobip\Model\SmsTextualMessage;

use Illuminate\Support\Facades\Cache;
use Infobip\Model\SmsAdvancedTextualRequest;

class UserController extends Controller
{
    public function updateUser(Request $request, $id_user) {
        $user = User::find($id_user);
        if ($user) {
            $user->name = $request->input('name');
            $user->gender = $request->input('gender') === 'male' ? 0 : 1;
            $user->date = $request->input('date');
            $user->address = $request->input('address');
            $user->save();
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
    public function send(Request $request)
    {
        $configuration = new Configuration(
            host: 'z1m6nx.api.infobip.com',
            apiKey: 'd1dd7a07ee4cb00ff226b26a219cc575-bc7935b7-938e-443b-a561-7374b4ae203f'
        );

        $brand_name = 'WellCare';
        $sendSmsApi = new SmsApi(config: $configuration);

        $phoneNumber = $request->phoneNumber;
        // $phoneNumber = '0339332612';
        $userExists = User::where('phone', $phoneNumber)->exists();
        $otp = random_int(100000, 999999);
        $expiration = now()->addSecond(30);
        $timeLogin = Carbon::now();

        DB::table('otp_login')->insert(['otp_code' => $otp, 'phoneNumber' => $phoneNumber, 'timeLogin' => $timeLogin, 'expiration' => $expiration, 'userExists' => $userExists]);
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
        } catch (ApiException $apiException) {
            return response()->json(['error' => 'Lỗi gửi OTP: ' . $apiException->getMessage()], 500);
        }
    }
    public function verify(Request $request)
    {
        $inputOtp = $request->otp;
        $otp_check = DB::table('otp_login')->where('otp_code', $inputOtp)->exists();
        if ($otp_check != false) {
            $data = DB::table('otp_login')->where('otp_code', $inputOtp)->first();
            $expiration = $data->expiration;
            $userExists = $data->userExists;
            $expiration = now()->addSecond(30);

            DB::table('otp_login')->where('otp_code', $inputOtp)->delete();
            if (now()->isAfter($expiration)) {
                // session()->forget('otp_' . $phoneNumber);
                return response()->json(['error' => 'OTP đã hết hạn'], 400);
            }
            if ($userExists != 0) {
                $user = User::where('phone', $data->phoneNumber)->first();
                DB::table('otp_login')->where('otp_code', $inputOtp)->delete();
                return response()->json([
                    'message' => 'Đăng nhập thành công',
                    'user' => $user
                ], 200);
            } else {
                $user = User::create([
                    'name' => $data->phoneNumber,
                    'phone' => $data->phoneNumber,
                    'password' => Hash::make($inputOtp),
                ]);
                DB::table('otp_login')->where('otp_code', $inputOtp)->delete();
                return response()->json([
                    'message' => 'Đăng nhập thành công',
                    'user' => $user
                ], 201);
            }
        } else {
            return response()->json(['error' => 'OTP không chính xác hoặc đã hết hạn'], 400);
        }
    }
}
