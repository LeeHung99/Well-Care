<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bills;
use App\Models\Products;
use App\Models\Vouchers;
use Illuminate\Http\Request;
use App\Models\Product_session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
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
            $productArr = [];
            for ($i = 0; $i < count($request->cart); $i++) {
                $value = $request->cart;
                $productArr[$value[$i]['id_product']] = [
                    'id_product' => $value[$i]['id_product'],
                    'quantity' => $value[$i]['quantity'],
                    'price' => $value[$i]['price'],
                ];
            }
            $formData = $request->formData;
            // return response()->json(['request' => $request->all(), 'message' => $formData['message'], 'request' => $request->voucherId]);
            $payUrl = $request->paymentMethod;
            $totalAmount = $request->totalPrice;
            
            $trueKeys = array_keys(array_filter($payUrl));
            if ($trueKeys[0] == 'cash') {
                $payment_status = 0;
            } else {
                $payment_status = 1;
            }


            if ($payUrl['cash'] == true) { // $payUrl['cash'] == true bị thay ra vì test trên postman
                // Kiểm tra dữ liệu đầu vào
                if (empty($productArr)) {
                    throw new \Exception('Không có sản phẩm trong đơn hàng');
                }
                $user = User::where('phone', $formData['number'])->exists();
                if (!$user) {
                    User::create([
                        'name' => $formData['number'],
                        'phone' => $formData['number'],
                        'password' => Hash::make($formData['number']),
                    ]);
                }

                // Tạo đơn hàng mới
                // $voucher = $request->voucher;
                $voucherId = $request->voucherId;
                if ($voucherId != 0) {
                    $voucher_get = Vouchers::where('id_voucher', $voucherId)->first();
                } else {
                    $voucher_get = [];
                }
                // $voucher_get = Vouchers::where('id_voucher', $voucherId)->first();
                if (!empty($voucher_get)) {
                    $voucher_get->count_voucher -= 1;
                    $voucher_get->save();
                }
                // return response()->json(['voucher_get' => $voucher_get]);

                $id_user = User::where('phone', $formData['number'])->first();
                $bill = Bills::create([
                    'id_user' => $id_user->id_user,
                    'transport_status' => 0,
                    'payment_status' => $payment_status,
                    'address' => $formData['address'],
                    'voucher' =>  $voucher_get ? $voucher_get['code'] : '', //$voucher_get['code'] ? $voucher_get['code'] :
                    'ghichu' => $formData['message'],
                    'total_amount' => $totalAmount, 
                ]);

                // Thêm chi tiết đơn hàng
                foreach ($productArr as $productId => $details) {
                    if (is_array($details)) {
                        $details = (object) $details;
                    }
                    $bill->details()->create([
                        'id_product' => $details->id_product, //$productId có thể thay cho $details->id_product
                        'quantity' => $details->quantity,
                        'price' => $details->price,
                        // 'total_amount' => $totalAmount,
                    ]);

                    $product = Products::find($productId);
                    if ($product) {
                        // Cập nhật số lượng còn lại
                        $product->in_stock -= $details->quantity;
                        // Cập nhật số lượng đã bán
                        $product->sold += $details->quantity;
                        $product->save();
                    }
                }

                Log::info('Đơn hàng đã được lưu thành công', ['bill_id' => $bill->id]);
                return response()->json(['success' => 'Đặt hàng thành công']);
            } elseif ($payUrl['vnpay'] == true) {

                $product_session_check = Product_session::where('phone_number', $formData['number'])->exists();
                if ($product_session_check) {
                    Product_session::where('phone_number', $formData['number'])->delete();
                }
                // $voucher = $request->voucher;
                $voucherId = $request->voucherId;
                if ($voucherId != 0) {
                    $voucher_get = Vouchers::where('id_voucher', $voucherId)->first();
                } else {
                    $voucher_get = [];
                }

                // return response()->json(['voucher_get' => $voucher_get, 'voucherCode' => $voucher_get['code']]);
                $phoneNumber = $formData['number'];
                $productSessionData = [];
                foreach ($productArr as $product) {
                    $productSessionData[] = [
                        'phone_number' => $phoneNumber,
                        'payment_status' => $payment_status,
                        'address' => $formData['address'],
                        'voucher' => $voucher_get ? $voucher_get['code'] : '',
                        'ghichu' => $formData['message'],
                        'total_amount' => $totalAmount,
                        'id_product' => $product['id_product'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price']
                    ];
                }
                Product_session::insert($productSessionData);
                return $this->vnPayPayment($request, $productArr, $trueKeys, $totalAmount, $phoneNumber);
            } elseif ($payUrl['momo'] == true) {

                $product_session_check = Product_session::where('phone_number', $formData['number'])->exists();
                if ($product_session_check) {
                    Product_session::where('phone_number', $formData['number'])->delete();
                }

                $voucherId = $request->voucherId;
                if ($voucherId != 0) {
                    $voucher_get = Vouchers::where('id_voucher', $voucherId)->first();
                } else {
                    $voucher_get = [];
                }

                $phoneNumber = $formData['number'];
                $productSessionData = [];
                foreach ($productArr as $product) {
                    $productSessionData[] = [
                        'phone_number' => $phoneNumber,
                        'payment_status' => $payment_status,
                        'address' => $formData['address'],
                        'voucher' => $voucher_get ? $voucher_get['code'] : '',
                        'ghichu' => $formData['message'],
                        'total_amount' => $totalAmount,
                        'id_product' => $product['id_product'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price']
                    ];
                }
                // Nếu là thanh toán qua MOMO, chuyển hướng đến phương thức thanh toán MOMO
                Product_session::insert($productSessionData);
                return $this->momoPayment($request, $productArr, $trueKeys, $totalAmount, $phoneNumber);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



    /**
     * 
     * VNPAY ZONE ==================================================>
     * 
     */


    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX";
        $phoneNumber = $request->phoneNumber;
        $inputData = $request->all();
        $productArr = Product_session::all();

        if (isset($inputData['vnp_SecureHash'])) {
            $vnp_SecureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHash']);

            ksort($inputData);
            $hashData = "";
            foreach ($inputData as $key => $value) {
                $hashData .= $key . "=" . urlencode($value) . '&';
            }
            $hashData = rtrim($hashData, '&');
            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($secureHash == $vnp_SecureHash) {

                if ($inputData['vnp_ResponseCode'] == '00' && $inputData['vnp_TransactionStatus'] == '00') {
                    try {
                        $productSessions = Product_session::where('phone_number', $phoneNumber)->get();

                        // return response()->json(['productSessions' => $productSessions]);
                        if ($productSessions->isEmpty()) {
                            throw new \Exception('Không có sản phẩm trong đơn hàng');
                        }

                        $voucher = Vouchers::where('code', $productSessions->first()->voucher)->first();
                        if ($voucher) {
                            $voucher->count_voucher -= 1;
                            $voucher->save();
                        }

                        $user = User::where('phone', $phoneNumber)->exists();
                        if (!$user) {
                            User::create([
                                'name' => $phoneNumber,
                                'phone' => $phoneNumber,
                                'password' => Hash::make($phoneNumber),
                            ]);
                        }

                        $id_user = User::where('phone', $phoneNumber)->first();

                        $bill = Bills::create([
                            'id_user' => $id_user['id_user'],
                            'transport_status' => 0,
                            'payment_status' => $productSessions->first()->payment_status,
                            'address' => $productSessions->first()->address,
                            'voucher' => $productSessions->first()->voucher ?: null,
                            'ghichu' => $productSessions->first()->ghichu,
                            'total_amount' => $productSessions->first()->total_amount,
                        ]);


                        foreach ($productSessions as $session) {
                            // return response()->json(['a' => $session['voucher']]);
                            $bill->details()->create([
                                'id_product' => $session->id_product,
                                'quantity' => $session->quantity,
                                'price' => $session->price,
                                // 'total_amount' => $session->total_amount,
                            ]);


                            $product = Products::find($session->id_product);
                            if ($product) {
                                $product->in_stock -= $session->quantity;
                                $product->sold += $session->quantity;
                                $product->save();
                            }
                        }


                        Product_session::where('phone_number', $phoneNumber)->delete();
                        Log::info('Đơn hàng đã được lưu thành công', ['bill_id' => $bill->id]);

                        // TH1
                        return redirect('http://wellcarepharmacy.shop?success=Thanh toán thành công');
                    } catch (\Exception $e) {
                        return response()->json(['success' => false, 'error' => 'Đơn hàng lưu thất bại: ' . $e->getMessage()]);
                    }
                } else {
                    return response()->json(['success' => false, 'error' => 'Giao dịch không thành công']);
                }
            } else {
                return response()->json(['success' => false, 'error' => 'Chữ ký không hợp lệ']);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'Thiếu thông tin bảo mật']);
        }
    }

    public function vnPayPayment(Request $request, $productArr, $trueKeys, $totalAmount, $phoneNumber)
    {
        try {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = route('vnpayCallback', ['phoneNumber' => $phoneNumber]); //'http://127.0.0.1:8000/api/vnpayCallback'; // 'http://localhost:3000/'; // Trang trả về để lưu DB hoặc hiển thị kết quả
            $vnp_TmnCode = "Z0W62YGW"; // Mã website tại VNPAY
            $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX"; // Chuỗi bí mật

            $vnp_TxnRef = rand(00, 9999); // Mã đơn hàng
            $vnp_OrderInfo = 'Thanh toán hóa đơn';
            $vnp_OrderType = 'billPayment';
            $vnp_Amount = $totalAmount * 100; // Số tiền nhân với 100
            $vnp_Locale = 'VN';
            $vnp_BankCode = "NCB";
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);
            $query = "";
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode((string)$value);
                $query .= urlencode($key) . "=" . urlencode((string)$value) . '&';
            }

            $query = rtrim($query, '&');
            $hashdata = ltrim($hashdata, '&');

            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;
            return response()->json(['redirectUrl' => $vnp_Url]);
            // return redirect($vnp_Url); // Chuyển hướng người dùng đến trang thanh toán VNPAY
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }





    /**
     * 
     * MOMO ZONE ==================================================>
     * 
     * 
     */


    public function momoPayment(Request $request, $productArr, $trueKeys, $totalAmount, $phoneNumber)
    {
        try {
            // // Thực hiện request POST đến MoMo và trả về response
            $response = $this->createMoMoPaymentRequest($request, $productArr, $trueKeys, $totalAmount, $phoneNumber);
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['payUrl'])) {
                    $payUrl = $responseData['payUrl'];
                    return response()->json(['payUrl' => $payUrl]);
                } else {
                    throw new \Exception('Không nhận được payUrl từ MoMo');
                }
            } else {
                throw new \Exception('Không thể tạo yêu cầu thanh toán với MoMo: ' . $response->body());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function createMoMoPaymentRequest(Request $request, array $productArr, $trueKeys, $totalAmount, $phoneNumber)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $totalAmount;
        $orderId = time() . "";
        $redirectUrl = route('momoCallback', ['phoneNumber' => $phoneNumber]);
        $ipnUrl =  route('momoCallback', ['phoneNumber' => $phoneNumber]);
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

        return Http::post($endpoint, $data);
    }

    public function momoCallback(Request $request)
    {
        $inputData = $request->all();
        $phoneNumber = $request->phoneNumber;
        if ($request->resultCode == '0') {
            $orderId = $request->orderId;
            $formData = $request->formData;
            $payUrl = $request->paymentMethod;
            $totalAmount = $request->totalAmount;

            $productSessions = Product_session::where('phone_number', $phoneNumber)->get();
            if ($productSessions) {
                if ($productSessions->isEmpty()) {
                    throw new \Exception('Không có sản phẩm trong đơn hàng');
                }

                $voucher = Vouchers::where('code', $productSessions->first()->voucher)->first();
                if ($voucher) {
                    $voucher->count_voucher -= 1;
                    $voucher->save();
                }

                $user = User::where('phone', $phoneNumber)->exists();
                if (!$user) {
                    User::create([
                        'name' => $phoneNumber,
                        'phone' => $phoneNumber,
                        'password' => Hash::make($phoneNumber),
                    ]);
                }

                $id_user = User::where('phone', $phoneNumber)->first();
                $bill = Bills::create([
                    'id_user' => $id_user['id_user'],
                    'transport_status' => 0,
                    'payment_status' => $productSessions->first()->payment_status,
                    'address' => $productSessions->first()->address,
                    'voucher' => $productSessions->first()->voucher ?: null,
                    'ghichu' => $productSessions->first()->ghichu,
                    'total_amount' => $productSessions->first()->total_amount,
                ]);
                foreach ($productSessions as $session) {
                    $bill->details()->create([
                        'id_product' => $session->id_product,
                        'quantity' => $session->quantity,
                        'price' => $session->price,
                        // 'total_amount' => $session->total_amount,
                    ]);


                    $product = Products::find($session->id_product);
                    if ($product) {
                        $product->in_stock -= $session->quantity;
                        $product->sold += $session->quantity;
                        $product->save();
                    }
                }


                Product_session::where('phone_number', $phoneNumber)->delete();
                Log::info('Đơn hàng đã được lưu thành công', ['bill_id' => $bill->id]);
                return redirect('http://wellcarepharmacy.shop?success=Thanh toán thành công');
                // return response()->json([
                //     'redirect' => 'http://localhost:3000',
                //     'message' => 'Thanh toán thành công'
                // ]);
            }
        } else {
            // Thanh toán thất bại
            return response()->json(['error' => 'Đặt hàng thất bại']);
        }
    }
}
