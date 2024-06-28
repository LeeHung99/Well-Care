<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AdminPostsController extends Controller
{
    public function index()
    {
        $perpage = 15;
        $posts = DB::table('posts')
            ->join('users', 'posts.id_user', '=', 'users.id_user')
            ->join('article_categories', 'posts.id_article_category', '=', 'article_categories.id_article_category')
            ->select('users.name as user_name', 'article_categories.name as catename', 'posts.*')
            ->paginate($perpage);
        return view('admin/post/listpost', ['posts' => $posts]);
    }
    public function createpost()
    {
        $catepost = DB::table('article_categories')->get();
        // dd($catepost);
        $user = DB::table('users')->get();
        return view('admin/post/creatpost', ['catepost' => $catepost, 'user' => $user]);
    }
    public function storepost(PostValid $request)
    {
        $title = $request['title'];
        $shortdes = $request['shortdes'];
        $description = $request['des'];
        $id_cate = $request['id_cate'];
        $avatar = $request['avatar'];
        DB::table('posts')->insert([
           'title' => $title,
           'short_des' => $shortdes,
           'description' => $description,
           'id_article_category' => $id_cate,
           'avatar' => $avatar,
           'id_user' => 1,
        ]);
        $request->session()->flash('thongbao', 'Thêm bài viết thành công');
        return redirect('/admin/post');
    }
    public function editpost(Request $request, string $id_post){
        $catepost = DB::table('article_categories')->get();
        $posts = DB::table('posts')->where('id_post', $id_post)->first();
        if ($posts==null){
            $request->session()->flash('thongbao','Không có loại này: '. $id_post);
            return redirect('/admin/post/listpost');
        }
        return view('admin/post/editpost', ['post' => $posts,'catepost' => $catepost]);
    }
    public function updatepost(PostValid $request, string $id_post) {
        $title = $request['title'];
        $shortdes = $request['shortdes'];
        $description = $request['des'];
        $id_cate = $request['id_cate'];
        $avatar = $request['avatar'];
        DB::table('posts')->where('id_post',$id_post)->update([
            'title' => $title,
            'short_des' => $shortdes,
            'description' => $description,
            'id_article_category' => $id_cate,
            'avatar' => $avatar,
            'id_user' => 1,
         ]);
         $request->session()->flash('thongbao', 'Cập nhật bài viết thành công');
        return redirect('/admin/post');
    }
    public function destroypost(Request $request, string $id_post) {   
        DB::table('posts')->where('id_post',$id_post)->delete();
        $request->session()->flash('thongbao', 'Xóa bài viết thành công');
        return redirect('/admin/post');
    }
}
