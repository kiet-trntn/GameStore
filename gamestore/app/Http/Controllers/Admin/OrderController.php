<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. HIỂN THỊ DANH SÁCH ĐƠN HÀNG
    public function index()
    {
        // Lấy đơn hàng mới nhất xếp lên đầu, kèm theo thông tin User
        // Phân trang 10 đơn 1 trang cho giao diện khỏi bị dài sọc
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // 2. CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG (Cứu nét khi có sự cố)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Đã cập nhật trạng thái hóa đơn ' . $order->order_code . ' thành công!');
    }
}
