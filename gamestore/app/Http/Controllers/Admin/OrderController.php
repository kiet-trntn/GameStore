<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. HIỂN THỊ DANH SÁCH & TÌM KIẾM ĐƠN HÀNG
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm
        $keyword = $request->input('keyword');

        // Khởi tạo query kéo theo thông tin user
        $query = Order::with('user');

        // Nếu có từ khóa, tìm theo Mã đơn HOẶC tìm xuyên qua bảng User (tên/email)
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('order_code', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function($userQuery) use ($keyword) {
                      $userQuery->where('name', 'like', "%{$keyword}%")
                                ->orWhere('email', 'like', "%{$keyword}%");
                  });
            });
        }

        // Lấy dữ liệu, phân trang và nhớ giữ lại từ khóa trên URL
        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
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
