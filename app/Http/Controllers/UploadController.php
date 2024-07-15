<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path('images/product'))) {
                mkdir(public_path('images/product'), 0777, true);
            }
            // Đường dẫn lưu file
            $path = public_path('images/product');

            // Di chuyển file vào thư mục
            $file->move($path, $filename);

            // URL công khai của hình ảnh
            $url = asset('images/product/' . $filename);


            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
