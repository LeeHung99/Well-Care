<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $cate;

    function __construct(Category $cate)
    {
        $this->cate = $cate;
    }

    function index()
    {
        $cate = $this->cate->category();
        $seCate = $this->seCate->seCategory();
        return response()->json([
                'cate' => $cate
            ], 200);
    }
}
