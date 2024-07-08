<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function comment(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                // 'id_user' => 'required|integer|exists:users,id',
                // 'id_product' => 'required|integer|exists:products,id',
                'content' => 'required|string|max:255',
            ]);

            // Làm sạch nội dung để tránh XSS
            $cleanContent = strip_tags($validatedData['content']);

            // Tạo comment mới
            $comment = Comments::create([
                'id_user' => $request->id_user,
                'id_product' => $request->id_product,
                'content' =>  $cleanContent, // $request->content,
            ]);

            // Log thành công
            \Log::info('Comment created successfully', ['comment_id' => $comment->id]);

            return response()->json(['success' => 'Thêm bình luận thành công', 'comment' => $comment], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Xử lý lỗi validation
            return response()->json(['error' => 'Dữ liệu không hợp lệ', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Log lỗi
            Log::error('Error creating comment: ' . $e->getMessage());

            // Trả về thông báo lỗi chung
            return response()->json(['error' => 'Thêm comment thất bại'], 500);
        }
    }
}
