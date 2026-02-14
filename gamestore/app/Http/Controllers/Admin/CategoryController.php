<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Models\Category;

class CategoryController extends Controller
{
    // 1. Hiển thị danh sách
    public function index(Request $request) {
        $keyword = $request->input('keyword');
        $status = $request->input('status');

        $categories = Category::latest();

        // -- Lọc tìm kiếm từ khóa --
        if ($keyword) {
            $categories->where('name', 'like', "%{$keyword}%");
        }

        // -- Lọc trạng thái (ĐÃ SỬA: Dùng if thay vì when để tránh lỗi logic số 0) --
        if ($status !== null && $status !== 'all') {
            $categories->where('is_active', $status);
        }

        $categories = $categories->paginate(5); 

        // Giữ lại tham số khi chuyển trang
        $categories->appends([
            'keyword' => $keyword,
            'status' => $status
        ]);

        return view('admin.categories.index', compact('categories'));
    }

    // 2. Lưu danh mục mới
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:categories,name|max:50',
        ], [
            'name.required' => 'Bạn quên nhập tên danh mục rồi!',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục mới thành công!');
    }

    // 3. Xóa mềm danh mục (vào thùng rác)
    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Đã xóa danh mục thành công!');
    }

    // 4. Hiển thị form sửa
    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // 5. Cập nhật dữ liệu
    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|max:50|unique:categories,name,'. $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục thành công!');
    }

    // 6. Thùng rác
    public function trash() {
        $categories = Category::onlyTrashed()->latest()->paginate(5);
        return view('admin.categories.trash', compact('categories'));
    }

    // 7. Khôi phục
    public function restore($id) {
        $category = Category::withTrashed()->find($id);
        
        if ($category) {
            $category->restore(); 
            return redirect()->back()->with('success', 'Đã khôi phục danh mục thành công!');
        }
        
        return redirect()->back()->with('error', 'Không tìm thấy danh mục!');
    }

    // 8. Xóa vĩnh viễn 
    public function forceDelete($id) {
        $category = Category::withTrashed()->find($id);
        
        if ($category) {
            $category->forceDelete(); 
            return redirect()->back()->with('success', 'Đã xóa vĩnh viễn danh mục!');
        }

        return redirect()->back()->with('error', 'Không tìm thấy danh mục!');
    }

    // 9. Bật tắt trạng thái (AJAX) - ĐÃ SỬA LỖI
    public function toggleStatus($id) {
        $category = Category::find($id);

        // SỬA 1: Thêm dấu $ vào trước biến category
        if (!$category) {
            // SỬA 2: Sửa chữ 'respone' thành 'response'
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy danh mục!'], 404);
        }

        // Đảo ngược trạng thái: Đang 1 thành 0, đang 0 thành 1
        $category->is_active = !$category->is_active;
        $category->save();

        // Trả về JSON để Javascript bên View nhận được
        return response()->json([
            'status' => 'success',
            'message' => $category->is_active ? 'Đã hiện danh mục!' : 'Đã ẩn danh mục!',
            'new_state' => $category->is_active
        ]);
    }
}