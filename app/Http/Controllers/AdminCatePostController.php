<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatePostValid;
use App\Models\Article_categories;
use App\Models\Posts;
use Illuminate\Http\Request;

class AdminCatePostController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $data = Article_categories::orderBy('created_at', 'desc')->paginate($perPage);
        // dd($data);
        return view('admin.cate_post.list_cate_post', compact('data'));
    }

    public function createView()
    {
        return view('admin.cate_post.create_cate_post');
    }

    public function store(CatePostValid $request)
    {
        try {
            // dd($request);
            $catePost = Article_categories::create([
                'name' => $request->name,
                'hide' => $request->hide
            ]);

            return redirect()->route('catepost')->with('success', 'Thêm thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating article categories: ' . $e->getMessage());
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Thêm thất bại'], 500);
        }
    }

    public function editView($id)
    {
        $data = Article_categories::where('id_article_category', $id)->first();
        if (!$data) {
            return response()->json(['error' => 'Không có danh mục tương tự']);
        }
        return view('admin.cate_post.edit_cate_post', compact('data'));
    }

    public function update(CatePostValid $request, $id)
    {
        try {
            $data = Article_categories::where('id_article_category', $id)->first();
            $data->update([
                'name' => $request->name,
                'hide' => $request->hide,
            ]);

            return redirect()->route('catepost')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating article category: ' . $e->getMessage());
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Cập nhật thất bại!'], 500);
        }
    }

    public function destroy($id)
    {
        $data = Article_categories::where('id_article_category', $id)->first();

        $postCount = Posts::where('id_article_category', $id)->count();
        if ($postCount > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục này vì có bài viết đang tham chiếu đến nó.');
        }

        $data->delete();
        return redirect()->back()->with('success', 'Xóa thành công!');
    }
}
