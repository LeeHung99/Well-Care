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
    function product()
    {
        $product = DB::table('products')->get();
        return response()->json([
            'product' => $product
        ], 200);
    }

    function productDetail($id_product = 0)
    {
        $productDetail = DB::table('products')
            ->where('id_product', $id_product)
            ->first();
        return response()->json([
            'productDetail' => $productDetail
        ], 200);
    }

    function productCate($id_category)
    {
        $productCate = DB::table('products')
        ->where('id_third_category',$id_category)
        ->get();
        return response()->json([
            'productCate' => $productCate
        ], 200);
    }
}
