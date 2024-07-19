<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AdminKhController extends Controller
{
    public function index()
    {     
        $perpage = 10;
        $kh = DB::table('users')
            ->where('role' , '0')
            ->orderByDesc('id_user')
            ->paginate($perpage);
        return view('admin/users/listkh', ['kh' => $kh]);
    }
}
