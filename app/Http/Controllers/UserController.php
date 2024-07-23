<?php

namespace App\Http\Controllers;

use Log;
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
    public function updateUser(Request $request, $id_user)
    {
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
            host: '2v51dp.api.infobip.com',
            apiKey: '2eb7ed915f697472f9eddbda405d069e-873cc948-cfda-44fd-9e9a-a60b94cb3ce4'
        );

        $brand_name = 'WellCare';
        $sendSmsApi = new SmsApi(config: $configuration);

        $phoneNumber = $request->phoneNumber;
        $userExists = User::where('phone', $phoneNumber)->exists();
        $otp = random_int(100000, 999999);
        $expiration = now()->addSeconds(30);
        $timeLogin = Carbon::now();

        DB::table('otp_login')->insert([
            'otp_code' => $otp,
            'phoneNumber' => $phoneNumber,
            'timeLogin' => $timeLogin,
            'expiration' => $expiration,
            'userExists' => $userExists
        ]);

        $message = new SmsTextualMessage(
            destinations: [new SmsDestination(to: '+84' . ltrim($phoneNumber, '0'))],
            from: $brand_name,
            text: "Mã OTP của bạn là: $otp. Mã có hiệu lực trong 30 giây."
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);

            \Log::info('Infobip Response', ['response' => json_encode($smsResponse)]);

            $messageResults = $smsResponse->getMessages();
            if (empty($messageResults)) {
                throw new \Exception('Không có thông tin về tin nhắn được trả về');
            }

            $message = $messageResults[0];
            $messageStatus = $message->getStatus();

            if ($messageStatus === null) {
                throw new \Exception('Không nhận được thông tin về trạng thái tin nhắn');
            }

            $statusCode = $messageStatus->getGroupId();
            $statusName = $messageStatus->getName();
            $statusDescription = $messageStatus->getDescription();

            // Kiểm tra trạng thái tin nhắn
            switch ($statusName) {
                case 'PENDING':
                case 'ACCEPTED':
                case 'DELIVERED':
                case 'PENDING_ACCEPTED':
                    return response()->json(['success' => 'Tin nhắn OTP đã được gửi thành công'], 200);
                case 'REJECTED':
                    $errorReason = $messageStatus->getDescription() ?? 'Unknown reason';
                    throw new \Exception("Tin nhắn bị từ chối: $errorReason");
                default:
                    // Log trạng thái không xác định để theo dõi
                    \Log::warning('Trạng thái tin nhắn không xác định', [
                        'statusName' => $statusName,
                        'statusDescription' => $statusDescription
                    ]);
                    // Trả về thành công nếu không phải là REJECTED
                    return response()->json(['success' => 'Tin nhắn OTP đã được gửi', 'status' => $statusName], 200);
            }
        } catch (\Exception $e) {
            \Log::error('SMS Sending Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 400);
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

            // return response()->json(['data' => $data]);
            DB::table('otp_login')->where('otp_code', $inputOtp)->delete();
            if (now()->isAfter($expiration)) {
                // session()->forget('otp_' . $phoneNumber);
                return response()->json(['error' => 'OTP đã hết hạn'], 400);
            }
            if ($userExists != 0) {
                $user = User::where('phone', $data->phoneNumber)->first();
                DB::table('otp_login')->where('phoneNumber', $data->phoneNumber)->delete();
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
                DB::table('otp_login')->where('phoneNumber', $data->phoneNumber)->delete();
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
