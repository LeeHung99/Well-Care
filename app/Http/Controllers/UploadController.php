<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('images/product'), $filename);

            return response()->json([
                'url' => asset('images/product/' . $filename)
            ]);
        }
        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
