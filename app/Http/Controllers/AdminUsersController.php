<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUsersController extends Controller
{
    public function index()
    {
        $perpage = 10;
        $secategory = DB::table('users')
            ->orderByDesc('id_user')
            ->paginate($perpage);
        return view('admin/se_category/listsecate', ['secategory' => $secategory]);
    }
}
