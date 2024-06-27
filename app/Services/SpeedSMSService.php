<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SpeedSMSService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('SPEEDSMS_API_KEY');
    }

    public function sendSMS($phoneNumber, $message)
    {
        $url = 'https://api.speedsms.vn/index.php/sms/send';

        try {
            $response = $this->client->post($url, [
                'auth' => [$this->apiKey, ''],
                'json' => [
                    'to' => [$phoneNumber],
                    'content' => $message,
                    'sms_type' => 2 // 2 là quảng cáo, 1 là OTP, 3 là CSKH
                ]
            ]);

            $responseBody = json_decode($response->getBody()->getContents());

            Log::info('SpeedSMS Response: ', (array)$responseBody);

            if ($responseBody->status == 'success') {
                return $responseBody;
            } else {
                Log::error('SpeedSMS Error: ' . $responseBody->message);
                return $responseBody;
            }
        } catch (\Exception $e) {
            Log::error('SpeedSMS Exception: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
