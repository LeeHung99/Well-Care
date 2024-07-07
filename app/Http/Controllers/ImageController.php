<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function deleteImage($id)
    {
        $image = Image::find($id);

        if (!$image) {
            abort(404);
        }

        // Xóa hình ảnh từ storage nếu cần
        // Example: unlink(public_path('images/' . $image->image_path));

        // Xóa khỏi database
        $image->delete();

        return redirect()->back(); // Redirect back to the previous page
    }
}
