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
    // ==========================================
    // 1. DANH SÁCH GAME & TÌM KIẾM ĐA NĂNG
    // ==========================================
    public function index(Request $request)
    {
        // Sử dụng eager loading 'category' để tối ưu hóa truy vấn (giảm số lần query DB)
        $query = Game::with('category');
    
        // Xử lý tìm kiếm theo nhiều trường: Tên, Nhà phát triển, Mô tả
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%") 
                  ->orWhere('developer', 'LIKE', "%$search%") 
                  ->orWhere('description', 'LIKE', "%$search%");
            });
        }
    
        // Lọc theo danh mục nếu người dùng chọn
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
    
        // Phân trang 10 mục và giữ lại các tham số tìm kiếm trên thanh địa chỉ
        $games = $query->latest()->paginate(10)->appends($request->all());
        
        // Lấy danh sách danh mục đang hoạt động để hiển thị vào ô Filter Select
        $categories = Category::where('is_active', 1)->get();
    
        return view('admin.games.index', compact('games', 'categories'));
    }

    // ==========================================
    // 2. FORM TẠO MỚI GAME
    // ==========================================
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();   
        return view('admin.games.create', compact('categories'));
    }

    // ==========================================
    // 3. LƯU DỮ LIỆU GAME (XỬ LÝ ĐA PHƯƠNG TIỆN)
    // ==========================================
    public function store(Request $request)
    {
        // Validate dữ liệu chặt chẽ: chú ý logic sale_price < price
        $request->validate([
            'title'        => 'required|unique:games,title|max:255',
            'price'        => 'required|numeric|min:0',
            'sale_price'   => 'nullable|numeric|lt:price',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:5048', 
            'trailer_link' => 'nullable|url',
        ], [
            'title.unique'       => 'Tên game này đã tồn tại trên hệ thống.',
            'sale_price.lt'      => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'image.max'          => 'Dung lượng ảnh tối đa là 5MB.',
        ]);

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
        $game->release_date = $request->release_date;
        $game->is_active = 1;

        // --- Xử lý Ảnh đại diện (Thumbnail) ---
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'thumbnail-' . time() . '.' . $file->getClientOriginalExtension();
            // Lưu vào thư mục theo slug của game để dễ quản lý file vật lý
            $path = $file->storeAs("games/$slug", $fileName, 'public');
            $game->image = $path;
        }

        // --- Xử lý Bộ sưu tập ảnh (Screenshots - Multiple Upload) ---
        if ($request->hasFile('screenshots')) {
            $imagePaths = [];
            foreach ($request->file('screenshots') as $index => $file) {
                // Đánh số thứ tự cho screenshot để dễ theo dõi
                $fileName = 'screenshot-' . ($index + 1) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("games/$slug/screenshots", $fileName, 'public');
                $imagePaths[] = $path;
            }
            // Lưu mảng đường dẫn vào DB (Laravel sẽ tự cast sang JSON nếu được cấu hình trong Model)
            $game->screenshots = $imagePaths;
        }

        $game->save();
        return redirect()->route('admin.games.index')->with('success', 'Thêm game mới thành công!');
    }

    // ==========================================
    // 4. CẬP NHẬT THÔNG TIN GAME
    // ==========================================
    public function edit($id)
    {
        $game = \App\Models\Game::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $game = \App\Models\Game::findOrFail($id);
    
        $request->validate([
            'title' => 'required|max:255|unique:games,title,' . $id, // Bỏ qua check unique cho chính nó
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $game->title = $request->title;
        $slug = \Illuminate\Support\Str::slug($request->title);
        $game->slug = $slug;
        $game->price = $request->price;
        $game->sale_price = $request->sale_price;
        $game->developer = $request->developer;
        $game->release_date = $request->release_date; // Ép cái này về đúng tên input trong Blade
        $game->requirements = json_decode($request->requirements, true); // Dịch cái chữ từ textarea thành mảng để lưu
        $game->description = $request->description;
        $game->trailer_link = $request->trailer_link;
        $game->category_id = $request->category_id;
    
        // Cập nhật Ảnh đại diện: Xóa ảnh cũ trước khi lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($game->image && Storage::disk('public')->exists($game->image)) {
                Storage::disk('public')->delete($game->image);
            }
            $fileName = 'thumbnail-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $game->image = $request->file('image')->storeAs("games/$slug", $fileName, 'public');
        }
    
        // Cập nhật Bộ sưu tập: Xóa toàn bộ ảnh screenshots cũ và thay bằng bộ mới
        if ($request->hasFile('screenshots')) {
            if ($game->screenshots) {
                foreach ($game->screenshots as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $imagePaths = [];
            foreach ($request->file('screenshots') as $index => $file) {
                $fileName = 'screenshot-' . ($index + 1) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imagePaths[] = $file->storeAs("games/$slug/screenshots", $fileName, 'public');
            }
            $game->screenshots = $imagePaths;
        }
    
        $game->save();
        return redirect()->route('admin.games.index')->with('success', 'Cập nhật thành công!');
    }

    // ==========================================
    // 5. XÓA MỀM & QUẢN LÝ THÙNG RÁC
    // ==========================================
    public function destroy(string $id)
    {
        $game = \App\Models\Game::findOrFail($id);
        $game->delete(); // Đưa vào thùng rác (đánh dấu deleted_at)
        return redirect()->route('admin.games.index')->with('success', 'Đã chuyển vào thùng rác!');
    }

    public function trash()
    {
        $games = \App\Models\Game::onlyTrashed()->with('category')->latest()->paginate(10);
        return view('admin.games.trash', compact('games'));
    }

    public function restore($id)
    {
        $game = \App\Models\Game::withTrashed()->findOrFail($id);
        $game->restore(); // Khôi phục lại trạng thái bình thường
        return redirect()->route('admin.games.trash')->with('success', 'Khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $game = \App\Models\Game::withTrashed()->findOrFail($id);

        // Xóa hoàn toàn file vật lý để tiết kiệm dung lượng server
        if ($game->image) {
            \Storage::disk('public')->delete($game->image);
        }
        if ($game->screenshots) {
            foreach ($game->screenshots as $path) {
                \Storage::disk('public')->delete($path);
            }
        }

        $game->forceDelete(); // Xóa hoàn toàn khỏi Database
        return redirect()->route('admin.games.trash')->with('success', 'Đã xóa vĩnh viễn!');
    }

    // ==========================================
    // 6. CÁC HÀM CẬP NHẬT TRẠNG THÁI (AJAX)
    // ==========================================
    
    // Bật/tắt trạng thái ẩn hiện game
    public function toggleStatus($id)
    {
        $game = \App\Models\Game::find($id);
        if (!$game) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy!'], 404);
        }

        $game->is_active = !$game->is_active;
        $game->save();

        return response()->json([
            'status' => 'success',
            'message' => $game->is_active ? 'Đã hiển thị!' : 'Đã ẩn!',
            'new_state' => $game->is_active
        ]);
    }

    // Bật/tắt chế độ Game Hot (Nổi bật ngoài trang chủ)
    public function toggleFeatured($id)
    {
        $game = \App\Models\Game::find($id);
        if (!$game) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy!'], 404);
        }

        $game->is_featured = !$game->is_featured;
        $game->save();

        return response()->json([
            'status' => 'success',
            'message' => $game->is_featured ? 'Đã thêm vào Game Hot!' : 'Đã gỡ khỏi Game Hot!',
            'new_state' => $game->is_featured
        ]);
    }
}