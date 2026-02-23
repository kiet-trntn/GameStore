<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
            // Thêm validate ảnh: Phải là ảnh, tối đa 2MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.max' => 'Ảnh tối đa 2MB.',
        ]);
    
        $category = new Category();
        $category->name = $request->name;
        // Tạo slug nếu không nhập
        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->slug = $slug;
        $category->is_active = 1; // Mặc định hiện
    
        // --- XỬ LÝ ẢNH ---
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Tạo tên file theo slug để dễ quản lý: the-thao-123456.jpg
            $filename = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            // Lưu vào thư mục public/categories
            $path = $file->storeAs('categories', $filename, 'public');
            $category->image = $path;
        }
        // ----------------
    
        $category->save();
    
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // 3. Xóa mềm danh mục (vào thùng rác)
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
    
        // CHỈ XÓA MỀM - BỎ ĐOẠN XÓA ẢNH Ở ĐÂY
        $category->delete(); 
    
        return redirect()->route('admin.categories.index')->with('success', 'Đã chuyển danh mục vào thùng rác!');
    }

    // 4. Hiển thị form sửa
    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // 5. Cập nhật dữ liệu
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
    
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $category->name = $request->name;
        // Cập nhật slug mới nếu tên thay đổi (tuỳ chọn)
        $newSlug = Str::slug($request->name);
        $category->slug = $newSlug;
    
        // --- XỬ LÝ ẢNH KHI UPDATE ---
        // --- XỬ LÝ ẢNH KHI UPDATE ---
        if ($request->hasFile('image')) {
            // 1. Lấy đường dẫn gốc của ảnh cũ (tránh lỗi nếu Model có Accessor)
            $oldImagePath = $category->getRawOriginal('image');
            
            // Nếu có ảnh cũ thì thực hiện xóa thẳng luôn
            if (!empty($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            // 2. Lưu ảnh mới
            $file = $request->file('image');
            $filename = $newSlug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('categories', $filename, 'public');
            $category->image = $path;
        }
        // ---------------------------
    
        $category->save();
    
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
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
            // Lấy đường dẫn gốc và xóa file vật lý
            $imagePath = $category->getRawOriginal('image');
            if (!empty($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

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