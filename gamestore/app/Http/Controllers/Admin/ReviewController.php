<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // 1. HIỂN THỊ DANH SÁCH BÌNH LUẬN
    public function index()
    {
        // Kéo danh sách Review, nhớ lấy kèm thông tin User (người viết) và Game (bài được viết)
        // Xếp cái mới nhất lên đầu để dễ kiểm duyệt
        $reviews = Review::with(['user', 'game'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    // 2. XÓA BÌNH LUẬN SPAM (TRẢM NGAY TẠI CHỖ)
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['status' => 'success', 'message' => 'Đã quét sạch cái bình luận rác này!']);
    }
}
