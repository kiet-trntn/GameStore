<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    // Hiển thị trang chủ
    public function index() {
        // Lấy 4 danh mục đang hoạt động để sắp xếp vừa đủ cái grid mẫu
        // withCount('games') giúp đếm số game tự động (yêu cầu model Category phải có hàm games())
        $categories = Category::where('is_active', 1)
                                          ->withCount('games')
                                          ->take(4)
                                          ->get();
        return view('home.index', compact('categories'));
    }
}
