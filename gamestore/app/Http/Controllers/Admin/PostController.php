<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // 1. DANH SÁCH BÀI VIẾT
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Post::query();

        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('category', 'like', "%{$keyword}%");
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.posts.index', compact('posts'));
    }

    // 2. GIAO DIỆN THÊM BÀI MỚI
    public function create()
    {
        return view('admin.posts.create');
    }

    // 3. XỬ LÝ LƯU BÀI VIẾT
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Tự động tạo đường dẫn (slug) từ Tiêu đề (VD: "Game mới" -> "game-moi")
        $data['slug'] = Str::slug($request->title) . '-' . time(); // Ép thêm time() để chống trùng lặp tuyệt đối

        // Xử lý Upload Ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Đã lên sóng bài viết thành công!');
    }

    // 4. GIAO DIỆN SỬA BÀI
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    // 5. XỬ LÝ CẬP NHẬT
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Nếu sửa Tiêu đề thì cập nhật lại Slug luôn
        if ($request->title !== $post->title) {
            $data['slug'] = Str::slug($request->title) . '-' . time();
        }

        // Xử lý Upload Ảnh Mới (Và xóa ảnh cũ đi cho nhẹ máy chủ)
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Đã cập nhật bài viết cái rụp!');
    }

    // 6. XÓA BÀI VIẾT BẰNG AJAX
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Xóa luôn tấm ảnh trong thư mục storage để tiết kiệm dung lượng
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();

        return response()->json(['status' => 'success', 'message' => 'Đã đốt bài viết thành tro!']);
    }
}