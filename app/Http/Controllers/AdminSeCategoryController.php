<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
Paginator::useBootstrap();

class AdminSeCategoryController extends Controller
{
    public function index()
    {
        $perpage = 10;
        $secategory = DB::table('se_categories')
            ->join('category', 'se_categories.id_category', '=', 'category.id_category')
            ->select('category.name as cate_name', 'se_categories.name as secate_name', 'se_categories.*')
            ->orderByDesc('id_se_category')
            ->paginate($perpage);
        return view('admin/se_category/listsecate', ['secategory' => $secategory]);
    }
}
