<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    // Hàm hiển thị trang Giỏ hàng
    public function index()
    {
        // 1. Kiểm tra nếu chưa đăng nhập thì đá ra trang login
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để xem giỏ hàng nha ba!');
        }

        // 2. Lấy toàn bộ sản phẩm trong giỏ của User này (Kèm theo thông tin Game)
        $cartItems = Cart::with('game')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        // 3. Tính toán tổng tiền
        $subTotal = 0;
        foreach ($cartItems as $item) {
            // Nếu game có sale_price thì lấy sale, không có thì lấy price gốc
            $currentPrice = $item->game->sale_price ?? $item->game->price;
            
            // Do là game digital số lượng mặc định là 1, nhưng cứ nhân quantity cho chuẩn bài
            $subTotal += $currentPrice * $item->quantity; 
        }

        // 4. Trả dữ liệu ra giao diện
        return view('cart.index', compact('cartItems', 'subTotal'));
    }

    // HÀM XỬ LÝ: THÊM GAME VÀO GIỎ HÀNG
    public function add(Request $request, $game_id)
    {
        // 1. Kiểm tra đăng nhập (Bắt buộc)
        if (!Auth::check()) {
            // Trả về lỗi 401 (Chưa xác thực) nếu dùng AJAX, hoặc redirect về trang Login
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để mua game!'], 401);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua game!');
        }

        // 2. Kiểm tra Game có tồn tại và Đang hoạt động không
        $game = Game::where('id', $game_id)->where('is_active', 1)->firstOrFail();

        $user_id = Auth::id();

        // 3. Kiểm tra xem Game này đã nằm trong giỏ hàng của User chưa?
        $exists = Cart::where('user_id', $user_id)->where('game_id', $game_id)->exists();

        if ($exists) {
            if ($request->ajax()) {
                return response()->json(['status' => 'warning', 'message' => 'Game này đã có trong giỏ hàng của bạn rồi!']);
            }
            return back()->with('warning', 'Game này đã có trong giỏ hàng của bạn rồi!');
        }

        // 4. Lưu vào Database
        Cart::create([
            'user_id' => $user_id,
            'game_id' => $game_id,
            'quantity' => 1 // Mặc định là 1 vì bán Game Digital
        ]);

        // Đếm lại tổng số game trong giỏ để cập nhật cái icon Giỏ hàng trên Menu
        $cartCount = Cart::where('user_id', $user_id)->count();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Đã thêm ' . $game->title . ' vào giỏ!',
                'cart_count' => $cartCount
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng thành công!');
    }

    // HÀM XÓA GAME KHỎI GIỎ HÀNG (AJAX)
    public function remove(Request $request, $id)
    {
        // Tìm game trong giỏ của đúng user này
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->delete(); // Xóa cái rụp

            // Tính lại tổng tiền sau khi xóa
            $cartItems = Cart::with('game')->where('user_id', Auth::id())->get();
            $subTotal = 0;
            foreach ($cartItems as $item) {
                $subTotal += ($item->game->sale_price ?? $item->game->price) * $item->quantity;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Đã đá văng tựa game này khỏi giỏ!',
                'cart_count' => $cartItems->count(),
                'sub_total' => number_format($subTotal, 0, ',', '.') . '₫'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy game trong giỏ!'], 404);
    }

    // HÀM XỬ LÝ THANH TOÁN (CHỐT ĐƠN)
    public function checkout(Request $request)
    {
        $user_id = Auth::id();
        $cartItems = Cart::with('game')->where('user_id', $user_id)->get();

        // Check lỡ ai đó hack gọi API khi giỏ hàng trống
        if ($cartItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Giỏ hàng trống trơn lấy gì thanh toán ba?']);
        }

        try {
            // BẮT ĐẦU GIAO DỊCH (Nếu 1 trong các bước dưới bị lỗi, DB sẽ tự động hoàn tác (rollback) lại từ đầu)
            DB::beginTransaction(); 

            // 1. Tính tổng tiền
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += ($item->game->sale_price ?? $item->game->price) * $item->quantity;
            }

            // 2. Tạo Hóa đơn (Bảng orders)
            $order = Order::create([
                'user_id' => $user_id,
                'order_code' => 'GAMEX-' . strtoupper(Str::random(6)), // Random ra mã như GAMEX-A8F9K2
                'total_amount' => $totalAmount,
                'payment_method' => 'Thẻ tín dụng / Ví điện tử',
                'status' => 'completed' // Game digital mua là xong luôn
            ]);

            // 3. Tạo Chi tiết Hóa đơn (Bảng order_items)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'game_id' => $item->game_id,
                    'price' => $item->game->sale_price ?? $item->game->price // LƯU GIÁ LÚC MUA (Lỡ mai mốt game hết sale thì hóa đơn không bị đổi giá)
                ]);
            }

            // 4. Xóa sạch giỏ hàng của User này
            Cart::where('user_id', $user_id)->delete();

            // CHỐT ĐƠN THÀNH CÔNG -> Lưu vĩnh viễn mọi thứ nãy giờ vào Database
            DB::commit(); 

            return response()->json([
                'status' => 'success',
                'message' => 'Thanh toán thành công! Siêu phẩm đã nằm trong thư viện của bạn.',
                'order_code' => $order->order_code
            ]);

        } catch (\Exception $e) {
            // CÓ LỖI XẢY RA -> Hủy bỏ toàn bộ thao tác, không lưu bậy bạ
            DB::rollBack(); 
            
            return response()->json([
                'status' => 'error',
                'message' => 'Hệ thống bận, vui lòng thử lại! Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}