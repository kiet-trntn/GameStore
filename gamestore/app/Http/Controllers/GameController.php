<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function index() {
        return view('game.index');
    }

    // Hàm show nhận vào biến $slug từ thanh URL
    public function show($slug) {
        // 1. Tìm game đang hoạt động có slug tương ứng, kèm theo Danh mục (category)
        $game = Game::with('category')
                    ->where('slug', $slug)
                    ->where('is_active', 1)
                    ->firstOrFail(); // Không tìm thấy thì quăng lỗi 404

        // 2. MAGIC: Tự động cộng 1 vào cột views mỗi lần có người mở trang này!
        $game->increment('views');

        // 3. Lấy 4 Game Cùng Thể Loại (Gợi ý ở cuối trang)
        $relatedGames = Game::where('category_id', $game->category_id)
                            ->where('id', '!=', $game->id) // Né game hiện tại ra
                            ->where('is_active', 1)
                            ->inRandomOrder() // Lấy ngẫu nhiên
                            ->take(4)
                            ->get();

        // Đẩy dữ liệu ra giao diện
        return view('game.game_detail', compact('game', 'relatedGames'));
    }
}
