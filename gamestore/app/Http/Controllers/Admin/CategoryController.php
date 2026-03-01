<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // ==========================================
    // 1. HIỂN THỊ DANH SÁCH & TÌM KIẾM
    // ==========================================
    public function index(Request $request) {
        $keyword = $request->input('keyword');
        $status = $request->input('status');

        $categories = Category::latest();

        // Lọc theo từ khóa tên danh mục
        if ($keyword) {
            $categories->where('name', 'like', "%{$keyword}%");
        }

        // Lọc theo trạng thái ẩn/hiện (Bỏ qua nếu chọn 'all')
        if ($status !== null && $status !== 'all') {
            $categories->where('is_active', $status);
        }

        // Phân trang 5 mục và giữ lại các tham số lọc trên thanh URL
        $categories = $categories->paginate(5); 
        $categories->appends([
            'keyword' => $keyword,
            'status' => $status
        ]);

        return view('admin.categories.index', compact('categories'));
    }

    // ==========================================
    // 2. THÊM MỚI DANH MỤC (CÓ XỬ LÝ ẢNH)
    // ==========================================
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.max' => 'Ảnh tối đa 2MB.',
        ]);
    
        $category = new Category();
        $category->name = $request->name;
        
        // Tự động tạo slug từ tên (Ví dụ: "Hành Động" -> "hanh-dong")
        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->slug = $slug;
        $category->is_active = 1; 
    
        // Xử lý lưu file ảnh vào thư mục storage/app/public/categories
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('categories', $filename, 'public');
            $category->image = $path;
        }
    
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // ==========================================
    // 3. XÓA TẠM THỜI (SOFT DELETE)
    // ==========================================
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        // Chỉ đánh dấu xóa (deleted_at), không xóa file ảnh và không mất dữ liệu DB
        $category->delete(); 
        return redirect()->route('admin.categories.index')->with('success', 'Đã chuyển vào thùng rác!');
    }

    // ==========================================
    // 4 & 5. CẬP NHẬT DANH MỤC
    // ==========================================
    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
    
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $category->name = $request->name;
        $newSlug = Str::slug($request->name);
        $category->slug = $newSlug;
    
        // Nếu có tải lên ảnh mới: Xóa ảnh cũ trên đĩa rồi lưu ảnh mới
        if ($request->hasFile('image')) {
            $oldImagePath = $category->getRawOriginal('image'); // Lấy path gốc trong DB
            
            if (!empty($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $file = $request->file('image');
            $filename = $newSlug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('categories', $filename, 'public');
            $category->image = $path;
        }
    
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công!');
    }

    // ==========================================
    // 6 & 7. QUẢN LÝ THÙNG RÁC & KHÔI PHỤC
    // ==========================================
    public function trash() {
        // Chỉ lấy những danh mục đã bị xóa mềm
        $categories = Category::onlyTrashed()->latest()->paginate(5);
        return view('admin.categories.trash', compact('categories'));
    }

    public function restore($id) {
        $category = Category::withTrashed()->find($id);
        if ($category) {
            $category->restore(); // Gỡ bỏ đánh dấu xóa (set deleted_at = null)
            return redirect()->back()->with('success', 'Đã khôi phục thành công!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy!');
    }

    // ==========================================
    // 8. XÓA VĨNH VIỄN (FORCE DELETE)
    // ==========================================
    public function forceDelete($id) {
        $category = Category::withTrashed()->find($id);
        if ($category) {
            // Xóa file ảnh vật lý trong folder storage trước khi xóa DB
            $imagePath = $category->getRawOriginal('image');
            if (!empty($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $category->forceDelete(); // Xóa mất xác trong Database
            return redirect()->back()->with('success', 'Đã xóa vĩnh viễn!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy!');
    }

    // ==========================================
    // 9. BẬT/TẮT TRẠNG THÁI (DÙNG CHO AJAX)
    // ==========================================
    public function toggleStatus($id) {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy!'], 404);
        }

        // Đảo trạng thái: 1 -> 0 hoặc 0 -> 1
        $category->is_active = !$category->is_active;
        $category->save();

        // Trả về kết quả để giao diện Frontend xử lý mà không cần load lại trang
        return response()->json([
            'status' => 'success',
            'message' => $category->is_active ? 'Đã hiện danh mục!' : 'Đã ẩn danh mục!',
            'new_state' => $category->is_active
        ]);
    }
}