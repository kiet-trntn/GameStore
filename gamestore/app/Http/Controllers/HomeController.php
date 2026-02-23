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

        // 2. Lấy danh sách Game Hot (Tạm lấy 8 game mới nhất đang active)
        $hotGames= Game::where('is_active', 1)
                         ->latest() // Mới nhất
                         ->take(5) // Lấy 8 game 
                         ->get();

        // 3. Highlight: Game Mới (Lấy 3 game mới nhất)
        $newGames = Game::where('is_active', 1)
                         ->latest()
                         ->take(3)
                         ->get();
        
        // 4. Highlight: Game Phổ Biến (Tạm thời lấy ngẫu nhiên 3 game để không trùng)
        $popularGames = Game::where('is_active', 1)
                         ->inRandomOrder()
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
