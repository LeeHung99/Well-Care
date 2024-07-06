<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{

    public function test_view_checkout(Request $request)
    {
        // return response()->json(['a' => 'a']);
        $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX";
        if ($request->get('vnp_SecureHash')) {
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
                // Lấy thông tin từ db
                $productArr = DB::table('product_session')->get();
                try {
                    // $this->saveOrderToDatabase($request, $productArr);
                    return response()->json(['succes' => 'Đơn hàng đã được lưu thành công']);
                    // return redirect('/order_view')->with('success', 'Đơn hàng đã được lưu thành công!');
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Đơn hàng lưu thất bại']);
                    // return redirect('/order_view')->with('error', $e->getMessage());
                }
            } else {
                return redirect('/order_view')->with('error', 'Thanh toán không thành công.');
            }
        }
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
        // dd($request);
        // return response()->json(['request' => $request, 'cart' => $request->cart, 'formData' => $request->formData, 'paymentMethod' => $request->paymentMethod]);
        try {
            /**
             * Cần truyền thêm tham số array $productArr vào function store
             * 
             * Cần FE đẩy lên 1 mảng tương tương tự $productArr với key = id_product, value = các thông tin cần trong   table bill_detail
             * 
             * $productArr đang là mảng tạm để chứ product trong bills
             */
            // $productArr = $request->cart;
            DB::table('product_session')->truncate();
            // $productArr = [];
            for ($i = 0; $i < count($request->cart); $i++) {
                $value = $request->cart;
                $productArr[$value[$i]['id_product']] = [
                    'quantity' => $value[$i]['quantity'],
                    'price' => $value[$i]['price'],
                ];

                DB::table('product_session')->insert([
                    'id_product' => $value[$i]['id_product'],
                    'quantity' => $value[$i]['quantity'],
                    'price' => $value[$i]['price'],
                ]);
            }

            // Kiểm tra phương thức thanh toán từ request
            $formData = $request->formData;
            $payUrl = $request->paymentMethod;
            $totalAmount = $request->totalAmount;
            $trueKeys = array_keys(array_filter($payUrl));
            if ($trueKeys[0] == 'cash') {
                $payment_status = 0;
            } else {
                $payment_status = 1;
            }
            if ($payUrl['cash'] == true) {
                // Kiểm tra dữ liệu đầu vào
                if (empty($productArr)) {
                    throw new \Exception('Không có sản phẩm trong đơn hàng');
                }
                // Tạo đơn hàng mới
                $bill = Bills::create([
                    'id_user' => 2, // Đảm bảo rằng user với id này tồn tại, thay id 2 thành auth()->id_user vì phải bắt đăng nhập
                    'transport_status' => 0,
                    'payment_status' => $payment_status, // Giá trị mặc định là 0 nếu không có
                    'address' => $formData['address'], // Giá trị mặc định là 'HCM' nếu không có
                    'voucher' => null, // Giá trị mặc định là 'huydeptrai' nếu không có
                ]);

                // Thêm chi tiết đơn hàng
                foreach ($productArr as $productId => $details) {

                    if (is_array($details)) {
                        $details = (object) $details;
                    }
                    // dd($productArr, $details, gettype($details), $details->id_product);
                    $bill->details()->create([
                        'id_product' => $productId,
                        'quantity' => $details->quantity,
                        'price' => $details->price,
                        'total_amount' => $totalAmount,
                    ]);
                }

                Log::info('Đơn hàng đã được lưu thành công', ['bill_id' => $bill->id]);
                return response()->json(['success' => 'Đặt hàng thành công']);

                // Log::error('Lỗi khi lưu đơn hàng: ' . $e->getMessage(), [
                //     // 'request' => $request->all(),
                //     'productArr' => $productArr
                // ]);
                // throw new \Exception('Lỗi khi lưu đơn hàng: ' . $e->getMessage());

                // return redirect()->route('order_view')->with('message', 'Đơn hàng đã được thanh toán thành công.');
            } elseif ($payUrl['vnpay'] == true) {
                // Nếu là thanh toán qua VNPAY, chuyển hướng đến phương thức thanh toán VNPAY
                return $this->vnPayPayment($request, $productArr, $trueKeys, $totalAmount);
            } elseif ($payUrl['momo'] == true) {
                // Nếu là thanh toán qua MOMO, chuyển hướng đến phương thức thanh toán MOMO
                return $this->momoPayment($request, $productArr, $trueKeys, $totalAmount);
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
            $response = $this->createMoMoPaymentRequest($request, $productArr);
            if ($response->successful()) {
                $payUrl = $response['payUrl'];
                $orderId = $response['orderId'];

                // Lưu thông tin đơn hàng tạm thời vào session hoặc cache
                Session::put('pending_order_' . $orderId, [
                    'request' => $request->all(),
                    'products' => $productArr
                ]);

                return redirect()->away($payUrl);
            } else {
                return back()->with('error', 'Không thể tạo yêu cầu thanh toán với MoMo.');
            }
        } catch (\Exception $e) {
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
        $amount = "10000"; // Đổi thành $request->total_price khi lấy từ request
        $orderId = time() . "";
        $redirectUrl = route('momo.callback'); //"http://127.0.0.1:8000/order_view";
        $ipnUrl =  route('momo.callback'); //"http://127.0.0.1:8000/order_view";
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

    public function momoCallback(Request $request)
    {
        // Kiểm tra trạng thái thanh toán từ MoMo
        if ($request->resultCode == '0') {
            // Thanh toán thành công
            $orderId = $request->orderId;
            // Lấy thông tin đơn hàng từ session hoặc cache
            $orderInfo = Session::get('pending_order_' . $orderId);

            if ($orderInfo) {
                // Lưu đơn hàng vào database
                // dd($orderInfo['request'], $orderInfo['products']);
                // $this->saveOrderToDatabase($request, $orderInfo['products']);

                // Xóa thông tin đơn hàng tạm thời
                Session::forget('pending_order_' . $orderId);

                return redirect()->route('order_view')->with('message', 'Đơn hàng đã được thanh toán thành công.');
            }
        } else {
            // Thanh toán thất bại
            return redirect()->route('order.failed')->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
        }
    }



    public function vnPayPayment(Request $request, $productArr, $trueKeys, $totalAmount)
    {
        try {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = 'http://localhost:3000/'; // Trang trả về để lưu DB hoặc hiển thị kết quả
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

    public function createVNPayPaymentRequest(Request $request, $productArr, $trueKeys, $totalAmount)
    {
        // $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        // $vnp_Returnurl = 'http://localhost:3000/'; //"http://127.0.0.1:8000/order_view"; // trang trả về để lưu db
        // $vnp_TmnCode = "Z0W62YGW"; //Mã website tại VNPAY 
        // $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX"; //Chuỗi bí mật

        // $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này 
        // $vnp_OrderInfo = 'Thanh toán hóa đơn'; // $request->input('order_desc')
        // $vnp_OrderType = 'billPayment'; // $_POST['order_type']
        // $vnp_Amount = $totalAmount;
        // $vnp_Locale = 'VN';
        // $vnp_BankCode = "NCB";
        // $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        // //Add Params of 2.0.1 Version
        // // $vnp_ExpireDate = 'abc';  // $request->input('txtexpire')
        // //Billing
        // // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // // $fullName = trim($_POST['txt_billing_fullname']);
        // // if (isset($fullName) && trim($fullName) != '') {
        // //     $name = explode(' ', $fullName);
        // //     $vnp_Bill_FirstName = array_shift($name);
        // //     $vnp_Bill_LastName = array_pop($name);
        // // }
        // // $vnp_Bill_Address=$_POST['txt_inv_addr1'];
        // // $vnp_Bill_City=$_POST['txt_bill_city'];
        // // $vnp_Bill_Country=$_POST['txt_bill_country'];
        // // $vnp_Bill_State=$_POST['txt_bill_state'];
        // // // Invoice
        // // $vnp_Inv_Phone=$_POST['txt_inv_mobile'];
        // // $vnp_Inv_Email=$_POST['txt_inv_email'];
        // // $vnp_Inv_Customer=$_POST['txt_inv_customer'];
        // // $vnp_Inv_Address=$_POST['txt_inv_addr1'];
        // // $vnp_Inv_Company=$_POST['txt_inv_company'];
        // // $vnp_Inv_Taxcode=$_POST['txt_inv_taxcode'];
        // // $vnp_Inv_Type=$_POST['cbo_inv_type'];
        // $inputData = array(
        //     "vnp_Version" => "2.1.0",
        //     "vnp_TmnCode" => $vnp_TmnCode,
        //     "vnp_Amount" => $vnp_Amount,
        //     "vnp_Command" => "pay",
        //     "vnp_CreateDate" => date('YmdHis'),
        //     "vnp_CurrCode" => "VND",
        //     "vnp_IpAddr" => $vnp_IpAddr,
        //     "vnp_Locale" => $vnp_Locale,
        //     "vnp_OrderInfo" => $vnp_OrderInfo,
        //     "vnp_OrderType" => $vnp_OrderType,
        //     "vnp_ReturnUrl" => $vnp_Returnurl,
        //     "vnp_TxnRef" => $vnp_TxnRef,

        //     // "vnp_ExpireDate" => $vnp_ExpireDate,
        //     // "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
        //     // "vnp_Bill_Email"=>$vnp_Bill_Email,
        //     // "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
        //     // "vnp_Bill_LastName"=>$vnp_Bill_LastName,
        //     // "vnp_Bill_Address"=>$vnp_Bill_Address,
        //     // "vnp_Bill_City"=>$vnp_Bill_City,
        //     // "vnp_Bill_Country"=>$vnp_Bill_Country,
        //     // "vnp_Inv_Phone"=>$vnp_Inv_Phone,
        //     // "vnp_Inv_Email"=>$vnp_Inv_Email,
        //     // "vnp_Inv_Customer"=>$vnp_Inv_Customer,
        //     // "vnp_Inv_Address"=>$vnp_Inv_Address,
        //     // "vnp_Inv_Company"=>$vnp_Inv_Company,
        //     // "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
        //     // "vnp_Inv_Type"=>$vnp_Inv_Type
        // );

        // if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        //     $inputData['vnp_BankCode'] = $vnp_BankCode;
        // }
        // // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        // //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        // // }

        // //var_dump($inputData);
        // ksort($inputData);
        // $query = "";
        // $i = 0;
        // $hashdata = "";
        // foreach ($inputData as $key => $value) {
        //     if ($i == 1) {
        //         $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        //     } else {
        //         $hashdata .= urlencode($key) . "=" . urlencode($value);
        //         $i = 1;
        //     }
        //     $query .= urlencode($key) . "=" . urlencode($value) . '&';
        // }

        // $vnp_Url = $vnp_Url . "?" . $query;
        // if (isset($vnp_HashSecret)) {
        //     $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
        //     $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        // }
        // $returnData = array(
        //     'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        // );
        // if ($request->input('payUrl')) {
        //     header('Location: ' . $vnp_Url);
        //     die();
        // } else {
        //     echo json_encode($returnData);
        // }
        // // vui lòng tham khảo thêm tại code demo
        // return response()->json(['message' => 'hihi']);



        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = 'http://127.0.0.1:8000/order_view'; // redirect('http://localhost:3000/');
        $vnp_TmnCode = "Z0W62YGW";
        $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX";

        $vnp_TxnRef = rand(00, 9999);
        $vnp_OrderInfo = 'Thanh toán hóa đơn';
        $vnp_OrderType = 'billPayment';
        $vnp_Amount = $totalAmount * 100; // Lưu ý: số tiền phải nhân với 100
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
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode((string)$value); // ép kiểu $value thành chuỗi
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode((string)$value); // ép kiểu $value thành chuỗi
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode((string)$value) . '&'; // ép kiểu $value thành chuỗi
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
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
        return response()->json(['message' => 'hihi']);
    }


    // public function vnpayReturn(Request $request)
    // {
    //     $vnp_HashSecret = "04NAW27CAY6P5MU57XM770ODFLXJHYPX";
    //     $vnp_SecureHash = $request->get('vnp_SecureHash');
    //     $inputData = $request->except('vnp_SecureHash');

    //     ksort($inputData);
    //     $hashData = "";
    //     foreach ($inputData as $key => $value) {
    //         $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
    //     }
    //     $hashData = rtrim($hashData, '&');

    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    //     if ($secureHash == $vnp_SecureHash) {
    //         // Lấy thông tin từ session
    //         $orderData = $request->session()->get('pending_order');
    //         // $orderData = Session::get('pending_order');
    //         Log::info('Session after setting:', ['pending_order' => $orderData]);
    //         // Log::info('Session ID:', ['id' => session()->getId()]);
    //         dd($orderData);
    //         try {
    //             // $this->saveOrderToDatabase($request, $productArr);
    //             return redirect('/order_view')->with('success', 'Đơn hàng đã được lưu thành công!');
    //         } catch (\Exception $e) {
    //             return redirect('/order_view')->with('error', $e->getMessage());
    //         }
    //     } else {
    //         return redirect('/order_view')->with('error', 'Thanh toán không thành công.');
    //     }
    // }



    public function saveOrderToDatabase($formData, $productArr, $trueKeys, $totalAmount)
    {
        return response()->json(['formData' => $formData, 'productArr' => $productArr, 'trueKeys' => $trueKeys, 'totalAmount' => $totalAmount]);
        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($productArr)) {
                throw new \Exception('Không có sản phẩm trong đơn hàng');
            }
            // Tạo đơn hàng mới
            $bill = Bills::create([
                'id_user' => 2, // Đảm bảo rằng user với id này tồn tại, thay id 2 thành auth()->id_user vì phải bắt đăng nhập
                'transport_status' => 0,
                'payment_status' => $trueKeys, // Giá trị mặc định là 0 nếu không có
                'address' => $formData['address'], // Giá trị mặc định là 'HCM' nếu không có
                'voucher' => $formData['name'], // Giá trị mặc định là 'huydeptrai' nếu không có
            ]);

            // Thêm chi tiết đơn hàng
            foreach ($productArr as $productId => $details) {
                if (is_array($details)) {
                    $details = (object) $details;
                }
                // dd($productArr, $details, gettype($details), $details->id_product);
                $bill->details()->create([
                    'id_product' => $details->id_product,
                    'quantity' => $details->quantity,
                    'price' => $details->price,
                    'total_amount' => $totalAmount,
                ]);
            }

            Log::info('Đơn hàng đã được lưu thành công', ['bill_id' => $bill->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu đơn hàng: ' . $e->getMessage(), [
                // 'request' => $request->all(),
                'productArr' => $productArr
            ]);
            throw new \Exception('Lỗi khi lưu đơn hàng: ' . $e->getMessage());
        }
    }
}
