<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
Paginator::useBootstrap();

class AdminCategoryController extends Controller
{
    public function index(){
        $perpage = 15;
        $category = DB::table('category')
            ->orderByDesc('id_category')
            ->paginate($perpage);
        return view('admin/category/listcate', ['category' => $category]);
    }
    public function createcategory(){
         return view('admin/category/creatcate');
    }
    public function storecategory(Request $request)
    {
        $name = $request['name'];
        $hide = $request['hide'];
        DB::table('category')->insert([
            'name' => $name,
            'hide' => $hide
        ]);
        $request->session()->flash('thongbao', 'Thêm danh mục thành công');
        return redirect('/admin/category');
    }
    public function editcategory(Request $request, string $id_category)
    {
        $category = DB::table('category')->where('id_category', $id_category)->first();
        if ($category == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_category);
            return redirect('/admin/category/listcate');
        }
        return view('admin/category/editcate', ['category' => $category]);
    }
    public function updatecategory(Request $request, string $id_category){
        $name = $request['name'];
        $hide = $request['hide'];
        DB::table('category')->where('id_category', $id_category)->update([
            'name' => $name,
            'hide' => $hide
        ]);
        $request->session()->flash('thongbao', 'Cập nhật danh mục thành công');
        return redirect('/admin/category');
    }
    public function destroycategory(Request $request, string $id_category)
    {
        DB::table('category')->where('id_category', $id_category)->delete();
        $request->session()->flash('thongbao', 'Xóa danh mục thành công');
        return redirect('/admin/category');
    }
}
