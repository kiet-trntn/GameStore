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

    // 1. HÀM TẠO ĐƠN VÀ ĐẨY SANG VNPAY
    public function checkout(Request $request)
    {
        $user_id = Auth::id();
        $cartItems = Cart::with('game')->where('user_id', $user_id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Giỏ hàng trống!']);
        }

        try {
            DB::beginTransaction(); 

            // Tính tiền
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += ($item->game->sale_price ?? $item->game->price) * $item->quantity;
            }

            // TẠO HÓA ĐƠN TRẠNG THÁI "PENDING" (Chờ thanh toán)
            $order_code = 'GAMEX-' . strtoupper(Str::random(6));
            $order = Order::create([
                'user_id' => $user_id,
                'order_code' => $order_code,
                'total_amount' => $totalAmount,
                'payment_method' => 'VNPay',
                'status' => 'pending' // Chờ VNPay xử lý xong mới đổi thành completed
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'game_id' => $item->game_id,
                    'price' => $item->game->sale_price ?? $item->game->price 
                ]);
            }
            DB::commit(); 

            // ===== CODE TẠO LINK VNPAY CHUẨN QUỐC TẾ =====
            $vnp_Url = env('VNP_URL', "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html");
            $vnp_Returnurl = env('VNP_RETURN_URL', route('vnpay.return'));
            $vnp_TmnCode = env('VNP_TMN_CODE'); 
            $vnp_HashSecret = env('VNP_HASH_SECRET'); 

            $vnp_TxnRef = $order_code; 
            $vnp_OrderInfo = "Thanh toan don hang GameX: " . $order_code;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $totalAmount * 100; // VNPay quy định tiền phải nhân 100
            $vnp_Locale = 'vn';
            $vnp_BankCode = ''; // Dùng thẻ test của ngân hàng NCB
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

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

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            // Trả cái Link VNPay về cho Javascript chuyển trang
            return response()->json(['status' => 'success', 'url' => $vnp_Url]);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['status' => 'error', 'message' => 'Lỗi tạo đơn: ' . $e->getMessage()], 500);
        }
    }

    // 2. HÀM HỨNG KẾT QUẢ TỪ VNPAY TRẢ VỀ
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
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
            
            // Nếu giao dịch thành công (Mã 00)
            if ($request->vnp_ResponseCode == '00') {
                $order->update(['status' => 'completed']); // Chốt đơn!
                Cart::where('user_id', $order->user_id)->delete(); // Xóa giỏ hàng
                return redirect()->route('cart.index')->with('success', 'Thanh toán VNPay thành công! Game đã về thư viện!');
            } else {
                // Khách hủy giao dịch hoặc thẻ hết tiền
                $order->update(['status' => 'cancelled']);
                return redirect()->route('cart.index')->with('error', 'Thanh toán thất bại hoặc đã bị hủy!');
            }
        } else {
            return redirect()->route('cart.index')->with('error', 'Chữ ký VNPay không hợp lệ (Lỗi bảo mật)!');
        }
    }
}