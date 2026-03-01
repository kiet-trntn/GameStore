<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Auth: Thư viện quản lý đăng nhập, đăng xuất và kiểm tra session người dùng
use Illuminate\Support\Facades\Auth; 
// Hash: Thư viện dùng để mã hóa mật khẩu (không bao giờ lưu mật khẩu dạng chữ thô vào DB)
use Illuminate\Support\Facades\Hash;
// Gọi Model User để tương tác với bảng 'users' trong cơ sở dữ liệu
use App\Models\User; 


class AuthController extends Controller
{
    /**
     * 1. Hiển thị trang Đăng nhập
     * Nhiệm vụ: Chỉ đơn giản là trả về cái view (giao diện) login cho người dùng điền form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * 2. Xử lý logic Đăng nhập
     * Nhiệm vụ: Nhận dữ liệu từ form, kiểm tra tính đúng đắn, so khớp với Database.
     */
    public function login(Request $request)
    {
        // Bước A: Kiểm tra định dạng dữ liệu (Validation)
        // Nếu sai, Laravel tự động đá người dùng quay lại form kèm thông báo lỗi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Chưa nhập email',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Quên nhập mật khẩu rồi!'
        ]);

        // Bước B: Thử đăng nhập (Auth::attempt)
        // Nó sẽ lấy email tìm trong DB, sau đó lấy password người dùng nhập
        // mang đi băm (hash) rồi so sánh với password đã băm trong DB.
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            // Nếu đúng: Tạo lại ID Session để chống tấn công cố định phiên (Session Fixation)
            $request->session()->regenerate();

            // Chuyển hướng về trang người dùng định vào trước đó (intended) hoặc về trang chủ '/'
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công! Chiến game thôi!');
        }

        // Bước C: Thất bại (Sai email hoặc mật khẩu)
        // Quay lại trang trước đó kèm thông báo lỗi và giữ lại giá trị email cho người dùng đỡ phải gõ lại
        return back()->withErrors([
            'email' => 'Email hoặc mật mã không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * 3. Xử lý Đăng xuất
     * Nhiệm vụ: Xóa sạch dấu vết đăng nhập của người dùng.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Xóa thông tin đăng nhập trong Guard

        $request->session()->invalidate(); // Hủy toàn bộ dữ liệu trong Session hiện tại

        $request->session()->regenerateToken(); // Tạo lại mã CSRF token mới để bảo mật

        return redirect('/')->with('success', 'Đã đăng xuất an toàn!');
    }

    /**
     * 4. Hiển thị trang Đăng ký
     * Nhiệm vụ: Trả về giao diện form đăng ký thành viên mới.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * 5. Xử lý Đăng ký tài khoản mới
     * Nhiệm vụ: Kiểm tra dữ liệu đầu vào, lưu vào DB và tự động đăng nhập.
     */
    public function register(Request $request)
    {
        // Bước A: Kiểm tra dữ liệu cực gắt (Validation)
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users', // unique:users kiểm tra email đã tồn tại chưa
            'password' => 'required|string|min:8|confirmed', // confirmed: yêu cầu phải có ô password_confirmation khớp nhau
            'terms' => 'accepted' // Phải tích vào checkbox điều khoản
        ], [
            'name.required' => 'Gamertag không được để trống.',
            'name.max' => 'Gamertag tối đa 50 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng chuẩn.',
            'email.unique' => 'Email này đã có cao thủ khác đăng ký! Vui lòng chọn email khác.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự để đảm bảo an toàn.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp. Vui lòng gõ lại cẩn thận.',
            'terms.accepted' => 'Ba phải đồng ý với Điều khoản dịch vụ thì mới được chơi nha!'
        ]);

        // Bước B: Lưu người dùng mới vào Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Băm mật khẩu để bảo mật tuyệt đối
        ]);

        // Bước C: Tự động đăng nhập luôn cho người dùng vừa đăng ký
        Auth::login($user);

        // Bước D: Chuyển hướng kèm thông báo chào mừng
        return redirect()->intended('/')->with('success', 'Chào mừng thành viên mới gia nhập Biệt đội!');
    }
}