<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AdminSeCategoryController extends Controller
{
    public function index()
    {
        $perpage = 10;
        $secategory = DB::table('se_categories')
            ->join('category', 'se_categories.id_category', '=', 'category.id_category')
            ->select('category.name as cate_name', 'se_categories.name as secate_name', 'se_categories.*')
            ->orderByDesc('id_se_category')
            ->paginate($perpage);
        return view('admin/se_category/listsecate', ['secategory' => $secategory]);
    }
    public function createsecategory()
    {
        $cate = DB::table('category')->get();
        return view('admin/se_category/creatsecate', ['cate' => $cate]);
    }
    public function storesecategory(Request $request)
    {
        $name = $request['name'];
        $id_cate = $request['id_cate'];
        $hide = $request['hide'];
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
        DB::table('se_categories')->insert([
            'name' => $name,
            'id_category' => $id_cate,
            'hide' => $hide,
            'avatar' => $filename,
        ]);
        $request->session()->flash('thongbao', 'Thêm danh mục thành công');
        return redirect('/admin/secategory');
    }
    public function editsecategory(Request $request, string $id_se_category)
    {
        $cate = DB::table('category')->get();
        $secategory = DB::table('se_categories')->where('id_se_category', $id_se_category)->first();
        if ($secategory == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_se_category);
            return redirect('/admin/category/listcate');
        }
        return view('admin/se_category/editsecate', ['secategory' => $secategory, 'cate' => $cate]);
    }
    public function updatesecategory(Request $request, string $id_se_category)
    {
        $name = $request['name'];
        $id_cate = $request['id_cate'];
        $hide = $request['hide'];
        if ($request->hasFile('image')) {
            if (!is_dir(public_path('images/banner'))) {
                mkdir(public_path('images/banner'), 0777, true);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/category/' . $filename;
            $file->move(public_path('images/category'), $filename);
        } else {
            // $filename = null;
            $imageExist = DB::table('se_categories')->where('id_se_category', $id_se_category)->first();
            if ($imageExist) {
                $filename = $imageExist->avatar;
            }
        }
        DB::table('se_categories')->where('id_se_category', $id_se_category)->update([
            'name' => $name,
            'id_category' => $id_cate,
            'hide' => $hide,
            'avatar' => $filename,
        ]);
        $request->session()->flash('thongbao', 'Cập nhật danh mục thành công');
        return redirect('/admin/secategory');
    }
    public function destroysecategory(Request $request, string $id_se_category)
    {
        DB::table('se_categories')->where('id_se_category', $id_se_category)->delete();
        $request->session()->flash('thongbao', 'Xóa danh mục thành công');
        return redirect('/admin/secategory');
    }
}
