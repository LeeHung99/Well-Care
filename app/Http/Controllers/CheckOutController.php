<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

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
            /**
             * Cần truyền thêm tham số array $productArr vào function store
             * 
             * Cần FE đẩy lên 1 mảng tương tương tự $productArr với key = id_product, value = các thông tin cần trong   table bill_detail
             * 
             * $productArr đang là mảng tạm để chứ product trong bills
             */


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

            // session([
            //     'pending_order' => [
            //         'request' => $request->all(),
            //         'productArr' => $productArr,
            //     ]
            // ]);
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
            // Thực hiện tạo yêu cầu thanh toán với MoMo
            $response = $this->createMoMoPaymentRequest($request, $productArr);

            // dd($response, $request->successful());
            if ($response->successful()) {
                // Lấy đường dẫn thanh toán từ MoMo và redirect người dùng
                $payUrl = $response['payUrl'];

                session([
                    'pending_order' => [
                        'request' => $request->all(),
                        'productArr' => $productArr,
                        'orderId' => $response['orderId'] // Giả sử MoMo trả về orderId
                    ]
                ]);


                // Lưu thông tin đơn hàng vào database
                // $this->saveOrderToDatabase($request, $productArr);

                return redirect()->route('vnpay_return');
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


    public function vnPayPayment(Request $request, $productArr)
    {
        try {
            // Thực hiện tạo yêu cầu thanh toán với VNPAY
            $orderData = [
                'request' => $request->all(),
                'productArr' => $productArr,
            ];

            session(['pending_order' => $orderData]);
            // Cache::put('pending_order', $orderData, now()->addMinutes(30));
            // Cache::remember('pending_order', now()->addMinutes(30), function () use ($orderData) {
            //     return $orderData;
            // });
            $response = $this->createVNPayPaymentRequest($request, $productArr);
            if ($response->successful()) {
                // Lấy đường dẫn thanh toán từ VNPAY và redirect người dùng
                $payUrl = $response['payUrl'];
                // session([
                //     'pending_order' => [
                //         'request' => $request->all(),
                //         'productArr' => $productArr,
                //     ]
                // ]);

                return redirect()->route('vnpay_return');
            } else {
                // Xử lý khi request không thành công
                return back()->with('error', 'Không thể tạo yêu cầu thanh toán với VNPAY.');
            }
        } catch (\Exception $e) {
            // Xử lý khi có lỗi xảy ra
            return back()->with('error', $e->getMessage());
        }
    }

    public function createVNPayPaymentRequest(Request $request, $productArr)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/vnpay_return"; // trang trả về để lưu db
        $vnp_TmnCode = "Z0W62YGW"; //Mã website tại VNPAY 
        $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX"; //Chuỗi bí mật

        $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này 
        $vnp_OrderInfo = 'Thanh toán hóa đơn'; // $request->input('order_desc')
        $vnp_OrderType = 'billPayment'; // $_POST['order_type']
        $vnp_Amount = 10000 * 100;
        $vnp_Locale = 'VN';
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        // $vnp_ExpireDate = 'abc';  // $request->input('txtexpire')
        //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address=$_POST['txt_inv_addr1'];
        // $vnp_Bill_City=$_POST['txt_bill_city'];
        // $vnp_Bill_Country=$_POST['txt_bill_country'];
        // $vnp_Bill_State=$_POST['txt_bill_state'];
        // // Invoice
        // $vnp_Inv_Phone=$_POST['txt_inv_mobile'];
        // $vnp_Inv_Email=$_POST['txt_inv_email'];
        // $vnp_Inv_Customer=$_POST['txt_inv_customer'];
        // $vnp_Inv_Address=$_POST['txt_inv_addr1'];
        // $vnp_Inv_Company=$_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode=$_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type=$_POST['cbo_inv_type'];
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

            // "vnp_ExpireDate" => $vnp_ExpireDate,
            // "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
            // "vnp_Bill_Email"=>$vnp_Bill_Email,
            // "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
            // "vnp_Bill_LastName"=>$vnp_Bill_LastName,
            // "vnp_Bill_Address"=>$vnp_Bill_Address,
            // "vnp_Bill_City"=>$vnp_Bill_City,
            // "vnp_Bill_Country"=>$vnp_Bill_Country,
            // "vnp_Inv_Phone"=>$vnp_Inv_Phone,
            // "vnp_Inv_Email"=>$vnp_Inv_Email,
            // "vnp_Inv_Customer"=>$vnp_Inv_Customer,
            // "vnp_Inv_Address"=>$vnp_Inv_Address,
            // "vnp_Inv_Company"=>$vnp_Inv_Company,
            // "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
            // "vnp_Inv_Type"=>$vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        // }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if ($request->input('payUrl')) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
        return response()->json(['message' => 'hihi']);
    }


    public function vnpayReturn(Request $request)
    {
        // Log::info('vnpayReturn called', ['session' => session()->all()]);
        if (!Session::isStarted()) {
            Session::start();
        }
        // $orderData = session('pending_order');
        // Log::info('Before redirect - Session data', ['session' => session()->all()]);
        // $orderData = Cache::get('pending_order');
        // dd($orderData);
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
        $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX";
        $vnp_SecureHash = $request->get('vnp_SecureHash');
        $inputData = $request->except('vnp_SecureHash');

        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            // Lấy thông tin từ session
            $orderData = session('pending_order');
            try {
                $this->saveOrderToDatabase($request, $productArr);
                return redirect('/order_view')->with('success', 'Đơn hàng đã được lưu thành công!');
            } catch (\Exception $e) {
                return redirect('/order_view')->with('error', $e->getMessage());
            }
        } else {
            return redirect('/order_view')->with('error', 'Thanh toán không thành công.');
        }
    }



    public function saveOrderToDatabase(Request $request, $productArr)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($productArr)) {
                throw new \Exception('Không có sản phẩm trong đơn hàng');
            }

            $orderData = session('pending_order');
            // dd($orderData);
            // Tạo đơn hàng mới
            $bill = Bills::create([
                'id_user' => 2, // Đảm bảo rằng user với id này tồn tại
                'transport_status' => 0,
                'payment_status' => $request->input('payUrl', 0), // Giá trị mặc định là 0 nếu không có
                'address' => $request->input('address', 'HCM'), // Giá trị mặc định là 'HCM' nếu không có
                'voucher' => $request->input('voucher', 'huydeptrai'), // Giá trị mặc định là 'huydeptrai' nếu không có
            ]);

            // Thêm chi tiết đơn hàng
            foreach ($productArr as $productId => $details) {
                $bill->details()->create([
                    'id_product' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'total_amount' => $details['total_amount'],
                ]);
            }

            Log::info('Đơn hàng đã được lưu thành công', ['bill_id' => $bill->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu đơn hàng: ' . $e->getMessage(), [
                'request' => $request->all(),
                'productArr' => $productArr
            ]);
            throw new \Exception('Lỗi khi lưu đơn hàng: ' . $e->getMessage());
        }
    }
}
