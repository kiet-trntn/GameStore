<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Game;
use App\Models\Post;

class HomeController extends Controller
{
    // Hiển thị trang chủ
    public function index() {
        /// 1. Danh mục
        $categories = \App\Models\Category::where('is_active', 1)->withCount('games')->take(4)->get();
                                        
        // 2. Slider Game Hot (Chỉ lấy game ĐÃ PHÁT HÀNH)
        $hotGames = \App\Models\Game::where('is_active', 1)
                                    ->where('is_featured', 1)
                                    ->where(function($q) {
                                        $q->whereNull('release_date')
                                        ->orWhereDate('release_date', '<=', now());
                                    })
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // 3. Highlight: Game Mới (Chỉ lấy game ĐÃ PHÁT HÀNH, xếp theo mới nhất)
        $newGames = \App\Models\Game::where('is_active', 1)
                                    ->where(function($q) {
                                        $q->whereNull('release_date')
                                        ->orWhereDate('release_date', '<=', now());
                                    })
                                    ->latest()
                                    ->take(3)
                                    ->get();
        
        // 4. Highlight: Game Phổ Biến (Chỉ lấy game ĐÃ PHÁT HÀNH, xếp theo lượt xem)
        $popularGames = \App\Models\Game::where('is_active', 1)
                                        ->where(function($q) {
                                            $q->whereNull('release_date')
                                            ->orWhereDate('release_date', '<=', now());
                                        })
                                        ->orderBy('views', 'desc')
                                        ->take(3)
                                        ->get();
        
        // 5. Highlight: Sắp Ra Mắt (Chỉ lấy game CÓ NGÀY TRONG TƯƠNG LAI)
        $upcomingGames = \App\Models\Game::where('is_active', 1)
                                        ->whereNotNull('release_date')
                                        ->whereDate('release_date', '>', now())
                                        ->orderBy('release_date', 'asc') // Game nào sắp ra mắt nhất lên đầu
                                        ->take(3)
                                        ->get();

        // Lấy 1 bài viết MỚI NHẤT và NỔI BẬT NHẤT (dành cho cái hình to đùng bên trái)
        $featuredPost = Post::where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->first();

        // Lấy 3 bài viết tiếp theo (dành cho danh sách nhỏ bên phải)
        $recentPosts = Post::where('is_published', true)
                   ->where('id', '!=', $featuredPost->id ?? 0) // Loại trừ cái bài đã lên hình bự
                   ->orderBy('created_at', 'desc')
                   ->take(3)
                   ->get();

        return view('home.index', compact('categories', 'hotGames', 'newGames', 'popularGames', 'upcomingGames', 'featuredPost', 'recentPosts'));
    }
}
