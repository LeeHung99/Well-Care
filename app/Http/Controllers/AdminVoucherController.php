<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVoucherController extends Controller
{
    public function index()
    {
        $perpage = 10;
        $vouchers = DB::table('vouchers')
            ->orderByRaw('id_voucher')
            ->paginate($perpage);
        return view('admin/voucher/listvoucher', ['vouchers' => $vouchers]);
    }
    public function createvoucher()
    {
        return view('admin/voucher/creatvoucher');
    }
    public function storevoucher(Request $request)
    {
        $name = $request['name'];
        $code = $request['code'];
        $number = $request['number'];
        $count_voucher = $request['count_voucher'];
        $status = $request['status'];
        DB::table('vouchers')->insert([
            'name' => $name,
            'code' => $code,
            'number' => $number,
            'count_voucher' => $count_voucher,
            'status' => $status
        ]);
        $request->session()->flash('thongbao', 'Thêm ưu đãi thành công');
        return redirect('/admin/voucher');
    }
    public function editvoucher(Request $request, string $id_voucher)
    {
        $vouchers = DB::table('vouchers')->where('id_voucher', $id_voucher)->first();
        if ($vouchers == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_voucher);
            return redirect('/admin/voucher/listvoucher');
        }
        return view('admin/voucher/editvoucher', ['vouchers' => $vouchers]);
    }
    public function updatevoucher(Request $request, string $id_voucher){
        $name = $request['name'];
        $code = $request['code'];
        $number = $request['number'];
        $count_voucher = $request['count_voucher'];
        $status = $request['status'];
        DB::table('vouchers')->where('id_voucher', $id_voucher)->update([
            'name' => $name,
            'code' => $code,
            'number' => $number,
            'count_voucher' => $count_voucher,
            'status' => $status
        ]);
        $request->session()->flash('thongbao', 'Cập nhật ưu đãi thành công');
        return redirect('/admin/voucher');
    }
    public function destroyvoucher(Request $request, string $id_voucher)
    {
        DB::table('vouchers')->where('id_voucher', $id_voucher)->delete();
        $request->session()->flash('thongbao', 'Xóa mã ưu đãi thành công');
        return redirect('/admin/voucher');
    }
}
