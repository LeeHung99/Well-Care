<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private $cate;

    function __construct(Category $cate)
    {
        $this->cate = $cate;
    }

    function index($id_category = 0, $id_se_category = 0)
    {
        $cate = $this->cate->category();
        $seCate = DB::table('se_categories')
            ->where('id_category', $id_category)
            ->get();
        $thirdCate = DB::table('third_categories')
            ->where('id_se_category',$id_se_category)
            ->get();
        return response()->json([
            'cate' => $cate,
            'seCate' => $seCate,
            'thirdCate' => $thirdCate
        ], 200);
    }
}
