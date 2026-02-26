<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Cần để băm mật khẩu
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Nhớ thêm dòng này ở tuốt trên cùng file nha ba


class AuthController extends Controller
{
    // 1. Hiển thị trang Đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Xử lý Đăng nhập
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Chưa nhập email',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Quên nhập mật khẩu rồi!'
        ]);

        // Thử đăng nhập (so khớp DB)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            // Thành công thì đá về trang chủ
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công! Chiến game thôi!');
        }

        // Thất bại thì quay lại và báo lỗi
        return back()->withErrors([
            'email' => 'Email hoặc mật mã không chính xác.',
        ])->onlyInput('email');
    }

    // 3. Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đã đăng xuất an toàn!');
    }

    // 4. Hiển thị trang Đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // 5. Xử lý Đăng ký (Validate nghiêm túc)
    public function register(Request $request)
    {
        // Kiểm tra dữ liệu cực gắt
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users', // Bắt buộc không được trùng trong bảng users
            'password' => 'required|string|min:8|confirmed', // Mật khẩu >= 8 ký tự và phải khớp với ô Nhập lại
            'terms' => 'accepted' // Bắt buộc phải tick chọn ô Đồng ý điều khoản
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

        // Tạo tài khoản mới vào Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Bắt buộc phải mã hóa Hash
        ]);

        // Đăng nhập luôn cho tiện sau khi tạo xong
        Auth::login($user);

        // Đá về trang chủ và hiện thông báo
        return redirect()->intended('/')->with('success', 'Chào mừng thành viên mới gia nhập Biệt đội!');
    }
}