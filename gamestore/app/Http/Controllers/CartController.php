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
    // ==========================================
    // 1. HIỂN THỊ GIỎ HÀNG
    // ==========================================
    public function index()
    {
        // Kiểm tra đăng nhập: Phải là thành viên mới cho xem giỏ hàng
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để xem giỏ hàng nha ba!');
        }

        // Lấy danh sách game trong giỏ của User hiện tại
        // Eager Loading 'game' để tránh lỗi N+1 (truy vấn nhanh hơn)
        $cartItems = Cart::with('game')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        // Duyệt qua danh sách để cộng dồn tổng tiền
        $subTotal = 0;
        foreach ($cartItems as $item) {
            // Ưu tiên lấy giá khuyến mãi nếu có, không thì lấy giá gốc
            $currentPrice = $item->game->sale_price ?? $item->game->price;
            $subTotal += $currentPrice * $item->quantity; 
        }

        // Đổ dữ liệu ra view cart.index
        return view('cart.index', compact('cartItems', 'subTotal'));
    }

    // ==========================================
    // 2. THÊM GAME VÀO GIỎ (AJAX & TRADITIONAL)
    // ==========================================
    public function add(Request $request, $game_id)
    {
        // Chặn người dùng chưa đăng nhập
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để mua game!'], 401);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua game!');
        }

        // Tìm game: Game phải tồn tại và đang được kích hoạt (is_active = 1)
        $game = Game::where('id', $game_id)->where('is_active', 1)->firstOrFail();
        $user_id = Auth::id();

        // Chống trùng: Game digital mỗi người thường chỉ mua 1 bản, không cho thêm lần nữa
        $exists = Cart::where('user_id', $user_id)->where('game_id', $game_id)->exists();
        if ($exists) {
            if ($request->ajax()) {
                return response()->json(['status' => 'warning', 'message' => 'Game này đã có trong giỏ hàng của bạn rồi!']);
            }
            return back()->with('warning', 'Game này đã có trong giỏ hàng của bạn rồi!');
        }

        // Lưu bản ghi vào bảng Carts
        Cart::create([
            'user_id' => $user_id,
            'game_id' => $game_id,
            'quantity' => 1 
        ]);

        // Trả về kết quả JSON cho AJAX để cập nhật UI ngay lập tức không cần load trang
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

    // ==========================================
    // 3. XÓA GAME KHỎI GIỎ (AJAX)
    // ==========================================
    public function remove(Request $request, $id)
    {
        // Tìm và xóa: Chỉ xóa đúng món đồ của chính user đó
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->delete(); 

            // Sau khi xóa, phải tính lại tổng tiền để giao diện cập nhật chính xác
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

    // ==========================================
    // 4. THANH TOÁN & TẠO LINK VNPAY
    // ==========================================
    public function checkout(Request $request)
    {
        $user_id = Auth::id();
        $cartItems = Cart::with('game')->where('user_id', $user_id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Giỏ hàng trống!']);
        }

        try {
            // Dùng Transaction: Nếu lưu Order lỗi thì không lưu OrderItem (đảm bảo sạch DB)
            DB::beginTransaction(); 

            // Tính tổng tiền đơn hàng
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += ($item->game->sale_price ?? $item->game->price) * $item->quantity;
            }

            // Tạo mã đơn hàng ngẫu nhiên (Ví dụ: GAMEX-A1B2C3)
            $order_code = 'GAMEX-' . strtoupper(Str::random(6));
            
            // Lưu thông tin hóa đơn tổng
            $order = Order::create([
                'user_id' => $user_id,
                'order_code' => $order_code,
                'total_amount' => $totalAmount,
                'payment_method' => 'VNPay',
                'status' => 'pending' 
            ]);

            // Lưu chi tiết từng sản phẩm trong hóa đơn
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'game_id' => $item->game_id,
                    'price' => $item->game->sale_price ?? $item->game->price 
                ]);
            }
            
            DB::commit(); // Mọi thứ ok, chốt lưu vào DB

            // --- KHỞI TẠO CẤU HÌNH VNPAY ---
            $vnp_Url = env('VNP_URL', "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html");
            $vnp_Returnurl = env('VNP_RETURN_URL', route('vnpay.return'));
            $vnp_TmnCode = env('VNP_TMN_CODE'); 
            $vnp_HashSecret = env('VNP_HASH_SECRET'); 

            $vnp_TxnRef = $order_code; 
            $vnp_OrderInfo = "Thanh toan don hang GameX: " . $order_code;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $totalAmount * 100; // VNPay tính theo đơn vị Đồng, không dùng số thập phân (nên x100)
            $vnp_Locale = 'vn';
            $vnp_BankCode = ''; 
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

            // Mảng dữ liệu gửi sang VNPay
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            // Sắp xếp mảng theo alphabet (Yêu cầu bắt buộc của VNPay)
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            // Tạo mã băm Secure Hash để VNPay kiểm tra tính toàn vẹn của dữ liệu
            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            
            // Trả về link cho Frontend để redirect người dùng sang cổng thanh toán VNPay
            return response()->json(['status' => 'success', 'url' => $vnp_Url]);

        } catch (\Exception $e) {
            DB::rollBack(); // Lỗi phát là hủy toàn bộ lệnh Insert nãy giờ
            return response()->json(['status' => 'error', 'message' => 'Lỗi tạo đơn: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 5. XỬ LÝ KHI VNPAY TRẢ KẾT QUẢ VỀ
    // ==========================================
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        // Xác thực chữ ký để đảm bảo kết quả này đúng là từ VNPay gửi về, không phải bị hack
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            $order = Order::where('order_code', $request->vnp_TxnRef)->first();
            
            // Nếu VNPay báo mã 00: Thanh toán thành công
            if ($request->vnp_ResponseCode == '00') {
                $order->update(['status' => 'completed']); 
                Cart::where('user_id', $order->user_id)->delete(); // Thanh toán xong thì dọn dẹp giỏ hàng
                return redirect()->route('checkout.success')->with('order_code', $order->order_code);
            } else {
                // Các mã lỗi khác (Khách hủy, không đủ tiền...): Cập nhật trạng thái 'cancelled'
                $order->update(['status' => 'cancelled']);
                return redirect()->route('cart.index')->with('error', 'Thanh toán thất bại hoặc đã bị hủy!');
            }
        } else {
            return redirect()->route('cart.index')->with('error', 'Chữ ký VNPay không hợp lệ!');
        }
    }

    // ==========================================
    // 6. TRANG HOÀN TẤT
    // ==========================================
    public function success()
    {
        // Chỉ cho phép vào nếu có mã đơn hàng từ session (vừa thanh toán xong)
        if (!session('order_code')) {
            return redirect()->route('home');
        }
        return view('cart.success');
    }
}