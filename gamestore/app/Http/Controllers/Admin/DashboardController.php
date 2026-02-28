<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Game;
use App\Models\Review;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. GOM SỐ LIỆU TỔNG QUAN (Chỉ tính đơn hàng Thành công)
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalGames = Game::count();

        // 2. LẤY DOANH THU 7 NGÀY GẦN NHẤT ĐỂ VẼ BIỂU ĐỒ
        $chartLabels = [];
        $chartData = [];
        
        // Lùi về 6 ngày trước + ngày hôm nay = 7 ngày
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m'); // Trả ra mảng [ '21/02', '22/02', ...]
            
            // Tính tổng tiền của ngày đó
            $dailyRevenue = Order::where('status', 'completed')
                                 ->whereDate('created_at', $date->toDateString())
                                 ->sum('total_amount');
            $chartData[] = $dailyRevenue;
        }

        // Lấy 5 đơn hàng mới nhất để hiện ra bảng Mini
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue', 'totalOrders', 'totalUsers', 'totalGames', 
            'chartLabels', 'chartData', 'recentOrders'
        ));
    }
}
