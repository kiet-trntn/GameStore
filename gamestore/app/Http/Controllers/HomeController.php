<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Game;

class HomeController extends Controller
{
    // Hiển thị trang chủ
    public function index() {
        // 1. Lấy 4 danh mục đang hoạt động để sắp xếp vừa đủ cái grid mẫu
        // withCount('games') giúp đếm số game tự động (yêu cầu model Category phải có hàm games())
        $categories = Category::where('is_active', 1)
                                          ->withCount('games')
                                          ->take(4)
                                          ->get();

        // 2. Lấy danh sách Game Hot (Chỉ lấy 5 game được đánh sao)
        $hotGames = \App\Models\Game::where('is_active', 1)
                                    ->where('is_featured', 1)
                                    ->latest()
                                    ->take(5) 
                                    ->get();

        // 3. Highlight: Game Mới (Lấy 3 game mới nhất)
        $newGames = Game::where('is_active', 1)
                         ->latest()
                         ->take(3)
                         ->get();
        
        // 4. Highlight: Game Phổ Biến (Lấy 3 game có lượt xem cao nhất)
        $popularGames = \App\Models\Game::where('is_active', 1)
                                    ->orderBy('views', 'desc') // Xếp theo lượt xem giảm dần
                                    ->take(3)
                                    ->get();
        
        // 5. Highlight: Sắp Ra Mắt (Tạm thời lấy 3 game xếp theo tên. Sau này ba thêm cột release_date thì mình đổi lại)
        $upcomingGames = Game::where('is_active', 1)
                         ->orderBy('title', 'desc')
                         ->take(3)
                         ->get();

        return view('home.index', compact('categories', 'hotGames', 'newGames', 'popularGames', 'upcomingGames'));
    }
}
