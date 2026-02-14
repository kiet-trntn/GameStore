<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Dùng để tạo slug
use App\Models\Category;

class CategoryController extends Controller
{
    // 1. Hiển thị danh sách
    public function index(Request $request) {
        // 1. Lấy từ khóa tìm kiếm từ URL (VD: ?keyword=hanh+dong)
        $keyword = $request->input('keyword');

        // 2. Query Builder "Xịn"
        $categories = Category::latest()
            ->when($keyword, function ($query, $keyword) {
                // Nếu có $keyword thì dòng này mới chạy
                return $query->where('name', 'like', "%{$keyword}%");
            })
            ->paginate(5); // 3. Mỗi trang chỉ hiện 5 mục (Thay vì lấy hết)

        // 4. Giữ lại tham số tìm kiếm khi chuyển trang (Fix lỗi mất keyword khi qua trang 2)
        $categories->appends(['keyword' => $keyword]);

        return view('admin.categories.index', compact('categories'));
    }

    // 2. Lưu danh mục mới
    public function store(Request $request) {
        // Validation: Đảm bảo không trùng tên, không để trống
        $request->validate([
            'name' => 'required|unique:categories,name|max:50',
        ], [
            'name.required' => 'Bạn quên nhập tên danh mục rồi!',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        // Tạo dữ liệu
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Tự động tạo slug (Ví dụ: "Hành động" -> "hanh-dong")
        ]);

        // Quay lại trang trước kèm thông báo
        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục mới thành công!');
    }

    // 3. Xóa danh mục
    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Đã xóa danh mục thành công!');
    }

    // 4. Hiển thị form sữa
    public function edit($id) {
        // Tìm danh mục theo ID, nếu không thấy sẽ báo lỗi 404
        $category = Category::findOrFail($id);

        // Trả về view sữa và truyền dữ liệu category sang
        return view('admin.categories.edit', compact('category'));
    }

    // 5. Cập nhật dữ liệu vào db
    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);

        // Validate: Tên bắt buộc, và không được trùng
        $request->validate([
            'name' => 'required|max:50|unique:categories,name,'. $id,
        ]);

        // Cập nhật
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        // Chuyển hướng về trang danh sách và báo thành công
        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục thành công!');
    }
}
