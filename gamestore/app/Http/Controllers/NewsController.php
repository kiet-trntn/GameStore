<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class NewsController extends Controller
{
    // 1. TRANG DANH SÁCH TIN TỨC
    public function index()
    {
        // Lấy các bài viết đã xuất bản, mới nhất lên đầu, phân trang 9 bài / trang
        $posts = Post::where('is_published', 1)->orderBy('created_at', 'desc')->paginate(9);
        return view('news.index', compact('posts'));
    }

    // 2. TRANG ĐỌC CHI TIẾT BÀI VIẾT
    public function show($slug)
    {
        // Tìm bài viết theo đường dẫn (slug)
        $post = Post::where('slug', $slug)->where('is_published', 1)->firstOrFail();
        
        // Lấy 3 bài viết cùng thể loại để gợi ý ở cuối trang
        $relatedPosts = Post::where('category', $post->category)
                            ->where('id', '!=', $post->id)
                            ->where('is_published', 1)
                            ->take(3)
                            ->get();

        return view('news.show', compact('post', 'relatedPosts'));
    }
}