<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image_products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    function cate()
    {
        $cate = DB::table('category')->get();
        $seCate = DB::table('se_categories')->get();
        $thirdCate = DB::table('third_categories')->get();
        /*  Huy   */
        $seById = [];
        $thirdById = [];

        foreach ($cate as $category) {
            $data = DB::table('se_categories')
                ->where('id_category', $category->id_category)
                ->get();

            $seById[$category->id_category] = $data;
        }

        foreach ($seCate as $seCategory) {
            $data = DB::table('third_categories')
                ->where('id_se_category', $seCategory->id_se_category)
                ->get();

            $thirdById[$seCategory->id_se_category] = $data;
        }

        return response()->json([
            'cate' => $cate,
            'seById' => $seById,
            'thirdById' => $thirdById,
        ], 200);

        /*  Huy   */
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


    /*  Huy   */
    function productSale()
    {
        $data = DB::table('products')
            ->orderBy('sale', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'productSale' => $data
        ], 200);
    }

    function productHot()
    {
        $data = DB::table('products')
            ->where('hot', 1)
            ->limit(5)
            ->get();
        return response()->json([
            'productHot' => $data
        ], 200);
    }

    function productSold()
    {
        $data = DB::table('products')
            ->orderBy('sold', 'desc')
            ->limit(5)
            ->get();
        return response()->json([
            'productSold' => $data
        ], 200);
    }

    function imgProduct($id)
    {
        $data = Image_products::where('id_image_product', $id)->get();
        return response()->json([
            'image_product' => $data
        ], 200);
    }

    function voucher()
    {
        $data = DB::table('vouchers')
            ->limit(4)
            ->get();
        return response()->json([
            'voucher' => $data
        ], 200);
    }

    function productCate($id_category)
    {
        $productCate = DB::table('products')
            ->where('id_third_category', $id_category)
            ->get();
        return response()->json([
            'productCate' => $productCate
        ], 200);
    }

    function bill()
    {
        $bill = DB::table('bills')
            ->get();
        return response()->json([
            'bill' => $bill
        ], 200);
    }
    function billdetail($id_bill)
    {
        $billdetail = DB::table('bill_details')
            ->where('id_bill', $id_bill)
            ->get();
        return response()->json([
            'billdetail' => $billdetail
        ], 200);
    }
    function sick()
    {
        $sick = DB::table('sick')
            ->get();
        return response()->json([
            'sick' => $sick
        ], 200);
    }
    function object()
    {
        $object = DB::table('object')
            ->get();
        return response()->json([
            'object' => $object
        ], 200);
    }
    function banner()
    {
        $banner = DB::table('image_banners')
            ->get();
        return response()->json([
            'banner' => $banner
        ], 200);
    }
    function productsick($id_sick)
    {
        $productsick = DB::table('products')
            ->where('id_sick', $id_sick)
            ->get();
        return response()->json([
            'productsick' => $productsick
        ], 200);
    }
    function test($id_cate)
    {
        $test = DB::table('third_categories')->where('id_se_category', $id_cate)->get();
        return response()->json([
            'test' => $test
        ], 200);
    }
    function articlePost()
    {
        $articlePost = DB::table('article_categories')
            ->get();
        return response()->json([
            'articlePost' => $articlePost
        ], 200);
    }
    function post()
    {
        $post = DB::table('posts')
            ->get();
        return response()->json([
            'post' => $post
        ], 200);
    }
    function postbycate($id_cate)
    {
        $postbycate = DB::table('posts')
            ->where('id_article_category', $id_cate)
            ->get();
        return response()->json([
            'postbycate' => $postbycate
        ], 200);
    }
    function postDetail($id_post)
    {
        $postdetail = DB::table('posts')
            ->where('id_post', $id_post)
            ->get();
        return response()->json([
            'postdetail' => $postdetail
        ], 200);
    }
    function user()
    {
        $user = DB::table('users')
            ->get();
        return response()->json([
            'user' => $user
        ], 200);
    }
    function userbyid($id_user)
    {
        $user = DB::table('users')
            ->where('id_user', $id_user)
            ->first();
        return response()->json([
            'user' => $user
        ], 200);
    }
    function comment()
    {
        $comment = DB::table('comments')
            ->get();
        return response()->json([
            'comment' => $comment
        ], 200);
    }
}
