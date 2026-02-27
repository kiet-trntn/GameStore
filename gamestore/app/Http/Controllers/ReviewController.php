<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $game_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Ba quên chọn số sao kìa!',
            'comment.required' => 'Viết vài chữ cảm nhận đi ba!',
        ]);

        $user_id = Auth::id();

        // 1. KIỂM TRA XEM ĐÃ MUA GAME NÀY CHƯA? (Logic lấy điểm A+ là đây)
        $hasBought = Order::where('user_id', $user_id)
                        ->where('status', 'completed')
                        ->whereHas('items', function ($query) use ($game_id) {
                            $query->where('game_id', $game_id);
                        })->exists();

        if (!$hasBought) {
            return back()->with('error', 'Khôn lỏi hả ba? Phải mua game rồi mới được phép đánh giá nha!');
        }

        // 2. KIỂM TRA XEM ĐÃ ĐÁNH GIÁ TRƯỚC ĐÓ CHƯA? (Mỗi người 1 game 1 lần thôi)
        $alreadyReviewed = Review::where('user_id', $user_id)->where('game_id', $game_id)->exists();
        if ($alreadyReviewed) {
            return back()->with('error', 'Ba đã đánh giá game này rồi, không được spam nha!');
        }

        // 3. LƯU ĐÁNH GIÁ VÀO DATABASE
        Review::create([
            'user_id' => $user_id,
            'game_id' => $game_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn ba đã để lại đánh giá tâm huyết!');
    }
}
