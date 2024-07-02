<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLogoController extends Controller
{
    protected   $position = [
        '0' => 'Logo',
        '1' =>  'Logo site',
    ];
    public function index()
    {
        $logo = DB::table('logo')
            ->orderByRaw('position')
            ->get();
        return view('admin/logo/listlogo', ['logo' => $logo, 'position' => $this->position]);
    }
    public function editlogo(Request $request, string $id_logo)
    {
        $logos = DB::table('logo')->where('id_logo', $id_logo)->first();
        if ($logos == null) {
            $request->session()->flash('thongbao', 'Không có loại này: ' . $id_logo);
            return redirect('/admin/logo/listlogo');
        }
        return view('admin/logo/editlogo', ['logos' => $logos, 'positions' => $this->position]);
    }
    public function updatelogo(Request $request, string $id_logo){
        $position = $request['position'];
       
        if ($request->hasFile('image')) {
            if (!is_dir(public_path('images/logo'))) {
                mkdir(public_path('images/logo'), 0777, true);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filePath = 'images/logo/' . $filename;
            $file->move(public_path('images/logo'), $filename);
        } else {
            $filename = null;
        }
        DB::table('logo')->where('id_logo', $id_logo)->update([
            'image' => $filename,
            'position' => $position,
        ]);

        $request->session()->flash('thongbao', 'Cập nhật logo thành công');
        return redirect('/admin/logo');
    }
}
