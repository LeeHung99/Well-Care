<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AdminBillController extends Controller
{
    protected $transport_status = [
        '0' => 'Đang xử lý',
        '1' =>  'Đang giao hàng',
        '2' => 'Giao hàng thành công',
        '3' => 'Đơn hàng bị hủy'
    ];
    protected $payment_status = [
        '0' => 'Chưa thanh toán',
        '1' => 'Đã thanh toán'
    ];
    public function index()
    {
        $perpage = 15;
        $bill = DB::table('bills')
            ->join('users', 'users.id_user', '=', 'bills.id_user')
            ->select(
                'users.name as user_name',
                'bills.*',
            )
            ->orderByDesc('id_bill')
            ->paginate($perpage);
    
        return view('admin/bill/listbill', [
            'bill' => $bill,
            'transport_status' => $this->transport_status,
            'payment_status' => $this->payment_status
        ]);
    }
    public function billdetail(Request $request, string $id_bill, string $id_user){
        $perpage = 15;
        $user = DB::table('users')->where('id_user', $id_user)->first();
        $bill = DB::table('bills')->where('id_bill', $id_bill)->first();
        $bill_detail = DB::table('bill_details')
        ->join('products' , 'products.id_product' , '=' , 'bill_details.id_product')
        ->select('products.*', 'bill_details.*')
        ->where('id_bill', $id_bill)
        ->paginate($perpage);
        if ($bill_detail == null) {
            $request->session()->flash('thongbao', 'Không có bill này: ' . $id_bill);
            return redirect('admin/bill/listbill');
        }
        return view('admin/bill/billdetail', [
            'user' => $user,
            'bill' => $bill,
            'bill_detail' => $bill_detail,
            'transport_status' => $this->transport_status,
            'payment_status' => $this->payment_status
        ]);
    }
}
