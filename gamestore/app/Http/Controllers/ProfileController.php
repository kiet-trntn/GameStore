<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // 1. HIỂN THỊ TRANG HỒ SƠ
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // 2. CẬP NHẬT THÔNG TIN (Tên, Email)
    public function update(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            // Bắt buộc Email không được trùng với người khác (trừ chính mình)
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
        ], [
            'name.required' => 'Ba chưa nhập Tên kìa!',
            'email.required' => 'Email không được để trống nha!',
            'email.unique' => 'Email này có người xài rồi ba ơi!',
        ]);

        // Lưu vào Database
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Đã cập nhật thông tin cá nhân thành công!');
    }

    // 3. ĐỔI MẬT KHẨU
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed', // 'confirmed' tự động check với ô password_confirmation
        ], [
            'current_password.required' => 'Ba phải nhập mật khẩu hiện tại!',
            'password.required' => 'Nhập mật khẩu mới đi ba!',
            'password.min' => 'Mật khẩu phải từ 8 ký tự trở lên nha!',
            'password.confirmed' => 'Hai ô mật khẩu mới không khớp nhau!',
        ]);

        $user = Auth::user();

        // Kiểm tra xem mật khẩu cũ có gõ đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng!']);
        }

        // Đổi mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công! Giờ thì an toàn rồi.');
    }
}