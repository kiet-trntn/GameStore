<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // 1. HIỂN THỊ DANH SÁCH THÀNH VIÊN
    public function index()
    {
        // Lấy danh sách user, thằng nào mới đăng ký thì xếp lên đầu. 
        // Phân trang 10 người / trang.
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. XÓA THÀNH VIÊN 
    public function destroy($id)
    {
        // Không cho phép Admin tự xóa chính mình (chống "tự hủy")
        if (auth()->id() == $id) {
            return response()->json(['status' => 'error', 'message' => 'Ba không thể tự xóa chính mình được!']);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'Đã tiễn thành viên này ra đảo!']);
    }

    // 3. CẬP NHẬT QUYỀN HẠN 
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        // Chống tự hủy: Không cho Admin tự giáng chức chính mình
        if (auth()->id() == $id) {
            return back()->with('error', 'Không thay đổi được admin');
        }

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Đã cập nhật chức vụ cho ' . $user->name . ' thành công!');
    }
}
