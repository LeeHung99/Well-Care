<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{ 

    function cate()
    {
        $cate = DB::table('category')->get(); 
        $seCate = DB::table('se_categories')->get(); 
        $thirdCate = DB::table('third_categories')->get(); 
        // dd($cate);
        return response()->json([
            'cate' => $cate,
            'se_cate' => $seCate,
            'third' => $thirdCate
        ], 200);
    }
}

