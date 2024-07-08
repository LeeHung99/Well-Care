<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function comment(Request $request)
    {

        // return response()->json(['success' => $request]);
        // $validatedData = $request->validate([
        //     'id_user' => 'required|integer|exists:users,id',
        //     'id_product' => 'required|integer|exists:products,id',
        //     'content' => 'required|string|max:255',
        // ]);

        $cleanContent = strip_tags($request->content);

        Comments::create([
            'id_user' => $request->id_user,
            'id_product' => $request->id_product,
            'content' => $cleanContent,
        ]);

        return response()->json(['success' => 'Thêm bình luận thành công']);
    }
}
