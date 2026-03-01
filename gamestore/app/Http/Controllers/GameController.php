<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    /**
     * HIỂN THỊ DANH SÁCH GAME + LỌC (FILTER)
     */
    public function index(Request $request) {
        // 1. Khởi tạo Query ban đầu: Chỉ lấy game đang hoạt động và ĐÃ RA MẮT
        // (Nếu release_date là null hoặc nhỏ hơn/bằng thời gian hiện tại)
        $query = Game::with('category')
                    ->where('is_active', 1)
                    ->where(function($q) {
                        $q->whereNull('release_date')
                          ->orWhereDate('release_date', '<=', now());
                    });

        // Đếm tổng số game hiện có (không tính các filter bên dưới) để làm thống kê
        $totalGames = \App\Models\Game::where('is_active', 1)->count();
                    
        // 2. Lọc theo DANH MỤC (Category)
        if ($request->has('category') && $request->category != '') {
            $categorySlug = $request->category;
            // Kiểm tra quan hệ: Chỉ lấy game thuộc danh mục có slug tương ứng
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 3. Lọc theo TỪ KHÓA tìm kiếm (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // 4. Lọc theo KHOẢNG GIÁ
        // COALESCE(sale_price, price): Nếu có giá sale thì lấy giá sale, nếu không lấy giá gốc để so sánh
        if ($request->has('price') && $request->price != '') {
            if ($request->price == 'under_250') {
                $query->whereRaw('COALESCE(sale_price, price) < 250000');
            } elseif ($request->price == '250_to_500') {
                $query->whereRaw('COALESCE(sale_price, price) BETWEEN 250000 AND 500000');
            } elseif ($request->price == 'over_500') {
                $query->whereRaw('COALESCE(sale_price, price) > 500000');
            }
        }

        // 5. Lọc game đang GIẢM GIÁ (On Sale)
        if ($request->has('on_sale') && $request->on_sale == 'true') {
            $query->whereNotNull('sale_price') 
                  ->whereRaw('sale_price < price'); // Đảm bảo thực sự có giảm giá
        }

        // 6. Thực thi Query, Phân trang 9 item/trang
        // appends($request->all()): Giữ lại các tham số lọc trên URL khi bấm sang trang 2, 3...
        $games = $query->latest()->paginate(9)->appends($request->all());

        // Lấy danh sách danh mục để hiển thị ở Sidebar (bộ lọc)
        $categories = \App\Models\Category::where('is_active', 1)->get();

        // 7. Xử lý yêu cầu AJAX: Nếu lọc bằng JavaScript thì chỉ trả về vùng chứa danh sách game
        if ($request->ajax()) {
            return view('game.partials.game_grid', compact('games', 'categories'))->render();
        }

        // 8. Lấy Top 4 game hot dựa trên lượt xem (Views)
        $topSellingGames = Game::with('category')
                            ->where('is_active', 1)
                            ->where(function($q) {
                                $q->whereNull('release_date')
                                  ->orWhereDate('release_date', '<=', now());
                            })
                            ->orderBy('views', 'desc') 
                            ->take(4) 
                            ->get();

        return view('game.index', compact('games', 'categories', 'topSellingGames', 'totalGames'));
    }

    /**
     * HIỂN THỊ CHI TIẾT GAME
     */
    public function show($slug) {
        // 1. Tìm game theo Slug (đường dẫn thân thiện)
        $game = Game::with('category')
                    ->where('slug', $slug)
                    ->where('is_active', 1)
                    ->firstOrFail(); // Trả về trang 404 nếu không tìm thấy

        // 2. Tăng lượt xem (Views): Mỗi lần load trang chi tiết thì cộng thêm 1
        $game->increment('views');

        // 3. Lấy 4 Game liên quan (cùng thể loại)
        $relatedGames = Game::where('category_id', $game->category_id)
                            ->where('id', '!=', $game->id) // Không lấy lại chính game đang xem
                            ->where('is_active', 1)
                            ->where(function($q) {
                                $q->whereNull('release_date')
                                  ->orWhereDate('release_date', '<=', now());
                            })
                            ->inRandomOrder() // Sắp xếp ngẫu nhiên để tạo sự mới mẻ
                            ->take(4)
                            ->get();

        return view('game.game_detail', compact('game', 'relatedGames'));
    }
}