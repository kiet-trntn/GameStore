<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function index(Request $request) {
        // Khởi tạo query lấy game đang hoạt động và ĐÃ RA MẮT
        $query = Game::with('category')
                    ->where('is_active', 1)
                    ->where(function($q) {
                        $q->whereNull('release_date')
                          ->orWhereDate('release_date', '<=', now());
                    });

        // Đếm tổng số game đang có để in ra chữ "Hiện có ... tựa game"
        $totalGames = \App\Models\Game::where('is_active', 1)->count();
                    
        // Nếu khách bấm từ Trang chủ -> Lọc theo Danh mục
        if ($request->has('category') && $request->category != '') {
            $categorySlug = $request->category;
            // Tìm trong bảng categories xem có slug này không
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Nếu khách xài ô Tìm kiếm (Từ khóa)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // LỌC THEO KHOẢNG GIÁ
        if ($request->has('price') && $request->price != '') {
            // Dùng COALESCE để ưu tiên lấy sale_price (nếu có), không có thì lấy price gốc để so sánh
            if ($request->price == 'under_250') {
                $query->whereRaw('COALESCE(sale_price, price) < 250000');
            } elseif ($request->price == '250_to_500') {
                $query->whereRaw('COALESCE(sale_price, price) BETWEEN 250000 AND 500000');
            } elseif ($request->price == 'over_500') {
                $query->whereRaw('COALESCE(sale_price, price) > 500000');
            }
        }

        // LỌC THEO TRẠNG THÁI GIẢM GIÁ
        if ($request->has('on_sale') && $request->on_sale == 'true') {
            $query->whereNotNull('sale_price') // Chỉ lấy game CÓ giá sale
                  ->whereRaw('sale_price < price'); // Đề phòng admin nhập bậy sale_price > price
        }

        // Lấy dữ liệu, phân trang mỗi trang 9 game (appends để giữ lại url khi chuyển trang)
        $games = $query->latest()->paginate(9)->appends($request->all());

        // NẾU LÀ YÊU CẦU AJAX (TỪ JAVASCRIPT)
        if ($request->ajax()) {
            // Chỉ trả về đoạn HTML chứa danh sách game
            return view('game.partials.game_grid', compact('games'))->render();
        }

        // Lấy toàn bộ danh mục đang hoạt động để in ra Sidebar
        $categories = \App\Models\Category::where('is_active', 1)->get();

        // Lấy Top 4 game Bán chạy (Tạm tính bằng Lượt xem - Views)
        // Chỉ lấy những game đã ra mắt
        $topSellingGames = Game::with('category')
                            ->where('is_active', 1)
                            ->where(function($q) {
                                $q->whereNull('release_date')
                                  ->orWhereDate('release_date', '<=', now());
                            })
                            ->orderBy('views', 'desc') // Xếp hạng theo Lượt xem giảm dần
                            ->take(4) // Lấy đúng 4 game cho vừa cái Grid
                            ->get();

        return view('game.index', compact('games', 'categories', 'topSellingGames', 'totalGames'));
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

        // 3. Lấy 4 Game Cùng Thể Loại (Gợi ý ở cuối trang, CHỈ LẤY GAME ĐÃ PHÁT HÀNH)
        $relatedGames = Game::where('category_id', $game->category_id)
                            ->where('id', '!=', $game->id) // Né game hiện tại ra
                            ->where('is_active', 1)
                            ->where(function($q) {         // <-- THÊM KHỐI CHẶN GAME SẮP RA MẮT Ở ĐÂY
                                $q->whereNull('release_date')
                                  ->orWhereDate('release_date', '<=', now());
                            })
                            ->inRandomOrder() // Lấy ngẫu nhiên
                            ->take(4)
                            ->get();

        // Đẩy dữ liệu ra giao diện
        return view('game.game_detail', compact('game', 'relatedGames'));
    }
}
