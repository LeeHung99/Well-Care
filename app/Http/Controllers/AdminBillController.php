<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
Paginator::useBootstrap();

class AdminBillController extends Controller
{
    public function index()
    {
        $perpage = 15;
        $bill = DB::table('bills')
            ->join('bill_details', 'bills.id_bill', '=', 'bill_details.id_bill')
            ->join('products', 'products.id_product', '=', 'bill_details.id_product')
            ->join('users', 'users.id_user', '=' , 'bills.id_user')
            ->select('users.name as user_name', 'products.name as productname', 'bills.*', 'products.*', 'bill_details.*' , 'users.*')
            ->orderByDesc('id_bill')
            ->paginate($perpage);
        return view('admin/post/listpost', ['bill' => $bill]);
    }
}
