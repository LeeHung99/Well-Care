<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $data = Comments::with('user', 'product')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        // dd($data, $data->user);
        return view('admin.comments.list_comment', compact('data'));
    }
    public function destroy(Request $request){
        $id = $request->id;
        $comment = Comments::where('id_comment', $id)->first();
        if ($comment) {
            $comment->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa');
        } else {
            return redirect()->back()->with('error', 'Không có sản phẩm tương tự');
        }
    }
}
