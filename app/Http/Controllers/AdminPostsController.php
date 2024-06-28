<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
Paginator::useBootstrap();

class AdminPostsController extends Controller
{
    public function index(){
        $perpage = 15;
        $posts = DB::table('posts')
        ->join('users','posts.id_user', '=' , 'users.id_user')
        ->join('article_categories' , 'posts.id_article_category', '=' , 'article_categories.id_article_category')
        ->select('users.name as user_name', 'article_categories.name as catename', 'posts.*')
        ->paginate($perpage);
        return view('admin/post/dspost',['posts' => $posts]);
    }
}
