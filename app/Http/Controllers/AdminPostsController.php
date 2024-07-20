<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

Paginator::useBootstrap();

class AdminPostsController extends Controller
{
    public function index(Request $request)
    {
        $perpage = 15;
        $search = $request->query('search');

        $postsQuery = DB::table('posts')
            ->join('users', 'posts.id_user', '=', 'users.id_user')
            ->join('article_categories', 'posts.id_article_category', '=', 'article_categories.id_article_category')
            ->select('users.name as user_name', 'article_categories.name as catename', 'posts.*')
            ->orderByDesc('id_post');

        if ($search) {
            $postsQuery->where(function ($query) use ($search) {
                $query->where('posts.title', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'like', '%' . $search . '%')
                    ->orWhere('article_categories.name', 'like', '%' . $search . '%');
            });
        }

        $posts = $postsQuery->paginate($perpage);

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

        if ($request->hasFile('avatar')) {
            if (!is_dir(public_path('images/post'))) {
                mkdir(public_path('images/post'), 0777, true);
            }
            $file = $request->file('avatar');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/post/' . $filename;
            $file->move(public_path('images/post'), $filename);
        } else {
            $filename = null;
        }
        DB::table('posts')->insert([
            'title' => $title,
            'short_des' => $shortdes,
            'description' => $description,
            'id_article_category' => $id_cate,
            'avatar' => $filename,
            'id_user' =>  Auth::user()->id_user,
        ]);

        $request->session()->flash('thongbao', 'Thêm bài viết thành công');
        return redirect('/admin/post');
    }

    public function editpost(Request $request, string $id_post)
    {
        $catepost = DB::table('article_categories')->get();
        $posts = DB::table('posts')->where('id_post', $id_post)->first();
        if ($posts == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_post);
            return redirect('/admin/post/listpost');
        }
        return view('admin/post/editpost', ['post' => $posts, 'catepost' => $catepost]);
    }
    public function updatepost(PostValid $request, string $id_post)
    {
        $title = $request['title'];
        $shortdes = $request['shortdes'];
        $description = $request['des'];
        $id_cate = $request['id_cate'];
        $avatar = $request['avatar'];

        $updatePost = [
            'title' => $title,
            'short_des' => $shortdes,
            'description' => $description,
            'id_article_category' => $id_cate,
            'id_user' => Auth::user()->id_user,
        ];

        if (!empty($avatar)) {
            $updatePost['avatar'] = $avatar;
        }

        DB::table('posts')->where('id_post', $id_post)->update($updatePost);
        $request->session()->flash('thongbao', 'Cập nhật bài viết thành công');
        return redirect('/admin/post');
    }
    public function destroypost(Request $request, string $id_post)
    {
        DB::table('posts')->where('id_post', $id_post)->delete();
        $request->session()->flash('thongbao', 'Xóa bài viết thành công');
        return redirect('/admin/post');
    }
}
