<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AdminBannerController extends Controller
{
    protected   $position = [
        '1' => 'Slide Banner',
        '2' =>  'Banner Header',
        '3' => 'Banner Footer',
        '4' => 'Banner Sale'
    ];
    public function index()
    {
        $perpage = 5;
        $banner = DB::table('image_banners')
            ->orderByRaw('position')
            ->paginate($perpage);
        return view('admin/banner/listbanner', ['banner' => $banner, 'position' => $this->position]);
    }
    public function createbanner()
    {
        return view('admin/banner/createbanner', ['positions' => $this->position]);
    }
    public function storebanner(Request $request)
    {
        $position = $request['position'];
       
        if ($request->hasFile('image')) {
            if (!is_dir(public_path('images/banner'))) {
                mkdir(public_path('images/banner'), 0777, true);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/banner/' . $filename;
            $file->move(public_path('images/banner'), $filename);
        } else {
            $filename = null;
        }
        DB::table('image_banners')->insert([
            'image' => $filename,
            'position' => $position,
        ]);

        $request->session()->flash('thongbao', 'Thêm bài viết thành công');
        return redirect('/admin/banner');
    }
    public function editbanner(Request $request, string $id_image_banner)
    {
        $banners = DB::table('image_banners')->where('id_image_banner', $id_image_banner)->first();
        if ($banners == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_image_banner);
            return redirect('/admin/banner/listbanner');
        }
        return view('admin/banner/editbanner', ['banners' => $banners, 'positions' => $this->position]);
    }
    public function updatebanner(Request $request, string $id_image_banner){
        $position = $request['position'];
       
        if ($request->hasFile('image')) {
            if (!is_dir(public_path('images/banner'))) {
                mkdir(public_path('images/banner'), 0777, true);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/banner/' . $filename;
            $file->move(public_path('images/banner'), $filename);
        } else {
            $filename = null;
        }
        DB::table('image_banners')->where('id_image_banner', $id_image_banner)->update([
            'image' => $filename,
            'position' => $position,
        ]);

        $request->session()->flash('thongbao', 'Cập nhật banner thành công');
        return redirect('/admin/banner');
    }
    public function destroybanner(Request $request, string $id_image_banner)
    {
        DB::table('image_banners')->where('id_image_banner', $id_image_banner)->delete();
        $request->session()->flash('thongbao', 'Xóa banner thành công');
        return redirect('/admin/banner');
    }
}
