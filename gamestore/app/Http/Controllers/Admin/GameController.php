<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\Category;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách game, đi kèm thông tin category để hiện tên loại game
        $games = \App\Models\Game::with('category')->latest()->paginate(10);

        return view('admin.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();   

        return view('admin.games.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Quy tắc kiểm tra (Validation Rules)
        $request->validate([
            'title'        => 'required|unique:games,title|max:255',
            'price'        => 'required|numeric|min:0',
            'sale_price'   => 'nullable|numeric|lt:price',
            'developer'    => 'nullable|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:5048', // Max 2MB
            'trailer_link' => 'nullable|url',
        ], [
            // 2. Thông báo lỗi tiếng Việt
            'title.required'     => 'Trường tên game là bắt buộc.',
            'title.unique'       => 'Tên game này đã tồn tại trên hệ thống.',
            'title.max'          => 'Tên game không được vượt quá 255 ký tự.',
            'price.required'     => 'Vui lòng nhập giá bán của sản phẩm.',
            'price.numeric'      => 'Giá bán phải là định dạng số.',
            'price.min'          => 'Giá bán không được nhỏ hơn 0.',
            'category_id.required' => 'Vui lòng chọn danh mục cho game.',
            'category_id.exists' => 'Danh mục đã chọn không tồn tại.',
            'image.image'        => 'Tập tin tải lên phải là định dạng hình ảnh.',
            'image.mimes'        => 'Ảnh chỉ chấp nhận các định dạng: jpeg, png, jpg.',
            'image.max'          => 'Dung lượng ảnh tối đa không được vượt quá 2MB.',
            'trailer_link.url'   => 'Đường dẫn trailer không hợp lệ (phải bắt đầu bằng http hoặc https).',
        ]);

        // 3. Nếu vượt qua kiểm tra, tiến hành lưu dữ liệu
        $game = new \App\Models\Game();
        $game->title = $request->title;
        $slug = \Illuminate\Support\Str::slug($request->title);
        $game->slug = $slug;
        $game->price = $request->price;
        $game->category_id = $request->category_id;
        $game->description = $request->description;
        $game->trailer_link = $request->trailer_link;
        $game->sale_price = $request->sale_price;
        $game->developer = $request->developer;
        $game->requirements = $request->requirements;
        $game->is_active = 1;

        // 1. Xử lý Ảnh chính (Thumbnail) - Lưu vào thư mục riêng của game đó
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Tên file: thumbnail-171567.jpg
            $fileName = 'thumbnail-' . time() . '.' . $file->getClientOriginalExtension();
            // Lưu vào: public/games/ten-game/thumbnail-xxx.jpg
            $path = $file->storeAs("games/$slug", $fileName, 'public');
            $game->image = $path;
        }

        // 2. Xử lý Bộ sưu tập ảnh (Screenshots) - Lưu có thứ tự 1, 2, 3...
        if ($request->hasFile('screenshots')) {
            $imagePaths = [];
            foreach ($request->file('screenshots') as $index => $file) {
                // Tên file theo thứ tự: screenshot-1.jpg, screenshot-2.jpg...
                $fileName = 'screenshot-' . ($index + 1) . '-' . time() . '.' . $file->getClientOriginalExtension();
                // Lưu vào: public/games/ten-game/screenshots/screenshot-1.jpg
                $path = $file->storeAs("games/$slug/screenshots", $fileName, 'public');
                $imagePaths[] = $path;
            }
            $game->screenshots = $imagePaths;
        }

        $game->save();

        return redirect()->route('admin.games.index')->with('success', 'Thêm game mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
