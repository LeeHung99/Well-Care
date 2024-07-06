<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AdminThirdCategoryController extends Controller
{
    public function index()
    {
        $perpage = 15;
        $thirdcategories = DB::table('third_categories')
            ->join('se_categories', 'third_categories.id_se_category', '=', 'se_categories.id_se_category')
            ->select('se_categories.name as secate_name', 'third_categories.name as thirdcate_name', 'third_categories.*')
            ->orderByDesc('id_third_category')
            ->paginate($perpage);
        return view('admin/third_category/listthirdcate', ['thirdcategories' => $thirdcategories]);
    }
    public function createthirdcategory()
    {
        $secate = DB::table('se_categories')->get();
        return view('admin/third_category/creatthirdcate', ['secate' => $secate]);
    }
    public function storethirdcategory(Request $request)
    {
        $name = $request['name'];
        $id_se_cate = $request['id_cate'];
        $order = $request['order'];
        $hide = $request['hide'];
        $hot = $request['hot'];
        if ($request->hasFile('image')) {
            if (!is_dir(public_path('images/banner'))) {
                mkdir(public_path('images/banner'), 0777, true);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/category/' . $filename;
            $file->move(public_path('images/category'), $filename);
        } else {
            $filename = null;
        }
        DB::table('third_categories')->insert([
            'name' => $name,
            'id_se_category' => $id_se_cate,
            'hide' => $hide,
            'order' => $order,
            'hot' => $hot,
            'avatar' => $filename,
        ]);
        $request->session()->flash('thongbao', 'Thêm danh mục thành công');
        return redirect('/admin/thirdcategory');
    }
    public function editthirdcategory(Request $request, string $id_third_category)
    {
        $secate = DB::table('se_categories')->get();
        $thirdcategories = DB::table('third_categories')->where('id_third_category', $id_third_category)->first();
        if ($thirdcategories == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_third_category);
            return redirect('/admin/category/listcate');
        }
        return view('admin/third_category/editthirdcate', ['thirdcategories' => $thirdcategories, 'secate' => $secate]);
    }
    public function updatethirdcategory(Request $request, string $id_third_category)
    {
        $name = $request['name'];
        $id_se_cate = $request['id_cate'];
        $order = $request['order'];
        $hide = $request['hide'];
        $hot = $request['hot'];
        if ($request->hasFile('image')) {
            if (!is_dir(public_path('images/banner'))) {
                mkdir(public_path('images/banner'), 0777, true);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/category/' . $filename;
            $file->move(public_path('images/category'), $filename);
        } else {
            $filename = null;
        }
        DB::table('third_categories')->where('id_third_category', $id_third_category)->update([
            'name' => $name,
            'id_se_category' => $id_se_cate,
            'hide' => $hide,
            'order' => $order,
            'hot' => $hot,
            'avatar' => $filename,
        ]);
        $request->session()->flash('thongbao', 'Cập nhật danh mục thành công');
        return redirect('/admin/thirdcategory');
    }
    public function destroythirdcategory(Request $request, string $id_third_category)
    {
        DB::table('third_categories')->where('id_third_category', $id_third_category)->delete();
        $request->session()->flash('thongbao', 'Xóa danh mục thành công');
        return redirect('/admin/thirdcategory');
    }
}
