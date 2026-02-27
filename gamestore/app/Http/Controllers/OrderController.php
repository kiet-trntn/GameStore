<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // HÀM LẤY LỊCH SỬ MUA HÀNG (THƯ VIỆN GAME)
    public function library()
    {
        // Kéo toàn bộ Đơn hàng của User đang đăng nhập
        // Dùng 'with' để kéo luôn danh sách Game (items) nằm trong đơn hàng đó cho lẹ
        $orders = Order::with('items.game')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc') // Đơn mới mua xếp lên đầu
                    ->get();

        return view('user.library', compact('orders'));
    }
}