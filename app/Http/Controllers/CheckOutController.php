<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckOutController extends Controller
{
    public function test_view_checkout()
    {
        return view('test_view_checkout');
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function store(Request $request)
    {
        try {
            $productArr = [
                3 => [
                    'quantity' => 1,
                    'price' => 10,
                    'total_amount' => 10,
                ],
                10 => [
                    'quantity' => 2,
                    'price' => 10,
                    'total_amount' => 10,
                ],
                12 => [
                    'quantity' => 3,
                    'price' => 10,
                    'total_amount' => 10,
                ],
                13 => [
                    'quantity' => 4,
                    'price' => 10,
                    'total_amount' => 10,
                ],
            ];
            // $validatedData = $request->validate([
            //     'name' => 'required|string|max:255',
            //     'phone' => 'required|string|max:15',
            //     // products là array chứa key = id_product, value là các thông tin như quantity, price, total_amount
            //     // 'products' => 'required',
            // ]);

            // check phương thức thanh toán
            // Kiểm tra phương thức thanh toán từ request
            if ($request->input('payUrl') == 1) {
            } elseif ($request->input('payUrl') == 2) {
                // Nếu là thanh toán qua VNPAY, chuyển hướng đến phương thức thanh toán VNPAY
                return $this->vnPayPayment($request, $productArr);
            } elseif ($request->input('payUrl') == 3) {
                // Nếu là thanh toán qua MOMO, chuyển hướng đến phương thức thanh toán MOMO
                return $this->momoPayment($request, $productArr);
            } else {
                // Xử lý khi phương thức thanh toán không hợp lệ
                return back()->with('error', 'Phương thức thanh toán không hợp lệ.');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function momoPayment(Request $request, $productArr)
    {
        try {
            // Lấy thông tin sản phẩm và đơn hàng từ request
            // $productArr = [
            //     3 => [
            //         'quantity' => 1,
            //         'price' => 10,
            //         'total_amount' => 10,
            //     ],
            //     10 => [
            //         'quantity' => 2,
            //         'price' => 10,
            //         'total_amount' => 20,
            //     ],
            //     12 => [
            //         'quantity' => 3,
            //         'price' => 10,
            //         'total_amount' => 30,
            //     ],
            // ];

            // Thực hiện tạo yêu cầu thanh toán với MoMo
            $response = $this->createMoMoPaymentRequest($request, $productArr);

            // dd($response, $request->successful());
            if ($response->successful()) {
                // Lấy đường dẫn thanh toán từ MoMo và redirect người dùng
                $payUrl = $response['payUrl'];

                // Lưu thông tin đơn hàng vào database
                $this->saveOrderToDatabase($request, $productArr);

                return redirect()->away($payUrl);
            } else {
                // Xử lý khi request không thành công
                return back()->with('error', 'Không thể tạo yêu cầu thanh toán với MoMo.');
            }
        } catch (\Exception $e) {
            // Xử lý khi có lỗi xảy ra
            return back()->with('error', $e->getMessage());
        }
    }

    private function createMoMoPaymentRequest(Request $request, array $productArr)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = "10000"; // Đổi thành $request->total_price khi bạn lấy từ request
        $orderId = time() . "";
        $redirectUrl = "http://127.0.0.1:8000/order_view";
        $ipnUrl = "http://127.0.0.1:8000/order_view";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        // Thực hiện request POST đến MoMo và trả về response
        return Http::post($endpoint, $data);
    }


    public function vnPayPayment(Request $request)
    {
        try {
            // Lấy thông tin sản phẩm và đơn hàng từ request
            $productArr = [
                3 => [
                    'quantity' => 1,
                    'price' => 10,
                    'total_amount' => 10,
                ],
                10 => [
                    'quantity' => 2,
                    'price' => 10,
                    'total_amount' => 20,
                ],
                12 => [
                    'quantity' => 3,
                    'price' => 10,
                    'total_amount' => 30,
                ],
            ];

            // Thực hiện tạo yêu cầu thanh toán với VNPAY
            $response = $this->createVNPayPaymentRequest($request, $productArr);

            if ($response->successful()) {
                // Lấy đường dẫn thanh toán từ VNPAY và redirect người dùng
                $payUrl = $response['payUrl'];

                // Lưu thông tin đơn hàng vào database
                $this->saveOrderToDatabase($request, $productArr);

                return redirect()->away($payUrl);
            } else {
                // Xử lý khi request không thành công
                return back()->with('error', 'Không thể tạo yêu cầu thanh toán với VNPAY.');
            }
        } catch (\Exception $e) {
            // Xử lý khi có lỗi xảy ra
            return back()->with('error', $e->getMessage());
        }
    }

    public function createVNPayPaymentRequest()
    {
        return response()->json(['message' => 'hihi']);
    }


    public function saveOrderToDatabase(Request $request, array $productArr)
    {
        // dd($productArr);
        // $bill = Bills::create([
        //     // 'id_user' => auth()->id(),
        //     'id_user' => $request->input('id_user'),
        //     'transport_status' => 0,
        //     'payment_status' => $request->input('pay'),
        //     'address' => $request->input('address'),
        //     'voucher' => $request->input('voucher')
        // ]);

        // // $validatedData['products'] là mảng chứa các product trong bill
        // // Thay $productArr = $validatedData['products'] 
        // // $productArr là mảng demo các product để lưu vào db
        // foreach ($productArr as $productId => $details) {
        //     $bill->details()->create([
        //         'id_product' => $productId, // $productId thay $details['id_product']
        //         'quantity' => $details['quantity'],
        //         'price' => $details['price'],
        //         'total_amount' => $details['total_amount'],
        //     ]);
        // }
    }
}
