<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Paginator::useBootstrap();

class AdminUsersController extends Controller
{
    protected   $role = [
        '1' => 'Admin',
        '2' =>  'Nhân viên',
        '3' => 'Người Viết Bài',
    ];
    public function index()
    {
        $perpage = 10;
        $nv = DB::table('users')
            ->where('role', '>', '0')
            ->orderByRaw('role')
            ->paginate($perpage);
        return view('admin/users/listuser', ['nv' => $nv, 'role' => $this->role]);
    }
    public function createusers()
    {
        return view('admin/users/creatuser', ['role' => $this->role]);
    }
    public function storeusers(Request $request)
    {
        $messages = [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Số điện thoại phải gồm 10 chữ số.',
            'address.required' => 'Địa chỉ là bắt buộc.',
            'date.required' => 'Ngày là bắt buộc.',
            'date.date' => 'Ngày không đúng định dạng.',
            'role.required' => 'Vai trò là bắt buộc.',
            'gender.required' => 'Giới tính là bắt buộc.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'required|string|max:255',
            'date' => 'required|date',
            'role' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect('/admin/createusers')
                ->withErrors($validator)
                ->withInput();
        }
        $name = $request['name'];
        $email = $request['email'];
        $password = $request['password'];
        $phone = $request['phone'];
        $address = $request['address'];
        $date = $request['date'];
        $role = $request['role'];
        $gender = $request['gender'];

        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'address' => $address,
            'date' => $date,
            'role' => $role,
            'gender' => $gender,
        ]);

        $request->session()->flash('thongbao', 'Thêm thành viên thành công');
        return redirect('/admin/users');
    }
    public function editusers(Request $request, string $id_user)
    {
        $nv = DB::table('users')->where('id_user', $id_user)->first();
        if ($nv == null) {
            $request->session()->flash('thongbao', 'Không có tài khoản này: ' . $id_user);
            return redirect('/admin/post/listpost');
        }
        return view('admin/users/edituser', ['nv' => $nv, 'role' => $this->role]);
    }
    public function updateusers(Request $request, string $id_user)
    {
        $name = $request['name'];
        $email = $request['email'];
        $password = $request['password'];
        $phone = $request['phone'];
        $address = $request['address'];
        $date = $request['date'];
        $role = $request['role'];
        $gender = $request['gender'];
        DB::table('users')->where('id_user', $id_user)->update([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'address' => $address,
            'date' => $date,
            'role' => $role,
            'gender' => $gender,
        ]);
        $request->session()->flash('thongbao', 'Cập nhật tài khoản thành công');
        return redirect('/admin/users');
    }
    public function destroyusers(Request $request, string $id_user)
    {
        DB::table('users')->where('id_user', $id_user)->delete();
        $request->session()->flash('thongbao', 'Xóa tài khoản thành công');
        return redirect('/admin/users');
    }
}
