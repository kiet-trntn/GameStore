<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Game;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * XỬ LÝ DỮ LIỆU HIỂN THỊ TẠI TRANG CHỦ
     */
    public function index() {
        
        // 1. LẤY DANH MỤC (CATEGORIES)
        // Lấy 4 danh mục đang hoạt động và đếm xem mỗi danh mục có bao nhiêu tựa game (withCount)
        $categories = \App\Models\Category::where('is_active', 1)->withCount('games')->take(4)->get();
                                        
        // 2. SLIDER GAME HOT (FEATURED)
        // Chỉ lấy những game được đánh dấu là 'Nổi bật' (is_featured = 1)
        // Quan trọng: Phải là game ĐÃ PHÁT HÀNH (release_date <= hiện tại hoặc là null)
        $hotGames = \App\Models\Game::where('is_active', 1)
                                    ->where('is_featured', 1)
                                    ->where(function($q) {
                                        $q->whereNull('release_date')
                                        ->orWhereDate('release_date', '<=', now());
                                    })
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // 3. GAME MỚI (NEW ARRIVALS)
        // Lấy 3 tựa game mới nhất vừa được thêm vào hệ thống (và đã phát hành)
        $newGames = \App\Models\Game::where('is_active', 1)
                                    ->where(function($q) {
                                        $q->whereNull('release_date')
                                        ->orWhereDate('release_date', '<=', now());
                                    })
                                    ->latest()
                                    ->take(3)
                                    ->get();
        
        // 4. GAME PHỔ BIẾN (POPULAR)
        // Lấy 3 tựa game có lượt xem (views) cao nhất để hiển thị
        $popularGames = \App\Models\Game::where('is_active', 1)
                                        ->where(function($q) {
                                            $q->whereNull('release_date')
                                            ->orWhereDate('release_date', '<=', now());
                                        })
                                        ->orderBy('views', 'desc')
                                        ->take(3)
                                        ->get();
        
        // 5. GAME SẮP RA MẮT (UPCOMING)
        // Ngược lại với các mục trên, mục này CHỈ lấy game có ngày phát hành trong tương lai
        // Sắp xếp 'asc' để game nào sắp đến ngày ra mắt nhất sẽ hiện lên đầu
        $upcomingGames = \App\Models\Game::where('is_active', 1)
                                        ->whereNotNull('release_date')
                                        ->whereDate('release_date', '>', now())
                                        ->orderBy('release_date', 'asc') 
                                        ->take(3)
                                        ->get();

        // 6. TIN TỨC NỔI BẬT (FEATURED POST)
        // Lấy bài viết mới nhất để làm "Spotlight" (thường hiển thị to nhất bên trái)
        $featuredPost = Post::where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->first();

        // 7. TIN TỨC LIÊN QUAN (RECENT POSTS)
        // Lấy 3 bài tiếp theo, trừ bài viết đã lấy ở mục số 6 (dùng where 'id' != ...)
        $recentPosts = Post::where('is_published', true)
                   ->where('id', '!=', $featuredPost->id ?? 0) 
                   ->orderBy('created_at', 'desc')
                   ->take(3)
                   ->get();

        // Đổ toàn bộ dữ liệu ra view home.index bằng hàm compact
        return view('home.index', compact('categories', 'hotGames', 'newGames', 'popularGames', 'upcomingGames', 'featuredPost', 'recentPosts'));
    }
}