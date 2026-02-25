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

    public function index(Request $request)
    {
        // 1. Khởi tạo query từ Model Game kèm theo quan hệ category
        $query = Game::with('category');
    
        // 2. Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            // Sử dụng group where để bao bọc các điều kiện OR
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%") // Tìm theo tên game
                  ->orWhere('developer', 'LIKE', "%$search%") // Tìm theo nhà phát triển
                  ->orWhere('description', 'LIKE', "%$search%"); // Tìm trong mô tả
            });
        }
    
        // 3. Kiểm tra nếu có lọc theo danh mục (Nếu ba muốn làm thêm)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
    
        // 4. Lấy dữ liệu và phân trang (appends giúp giữ từ khóa khi chuyển trang)
        $games = $query->latest()->paginate(10)->appends($request->all());
        
        // 5. Lấy danh sách danh mục để đổ vào ô Select lọc
        $categories = Category::where('is_active', 1)->get();
    
        return view('admin.games.index', compact('games', 'categories'));
    }


    public function create()
    {
        $categories = Category::where('is_active', 1)->get();   

        return view('admin.games.create', compact('categories'));
    }


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
            'release_date' => 'nullable|date',
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
        $game->release_date = $request->release_date;
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

    public function edit($id)
    {
        $game = \App\Models\Game::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $game = \App\Models\Game::findOrFail($id);
    
        // 1. Validate (Bỏ unique cho chính nó để không bị báo trùng tên cũ)
        $request->validate([
            'title' => 'required|max:255|unique:games,title,' . $id,
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // 2. Cập nhật thông tin chữ
        $game->title = $request->title;
        $slug = \Illuminate\Support\Str::slug($request->title);
        $game->slug = $slug;
        $game->price = $request->price;
        $game->sale_price = $request->sale_price;
        $game->developer = $request->developer;
        $game->requirements = $request->requirements;
        $game->description = $request->description;
        $game->trailer_link = $request->trailer_link;
        $game->category_id = $request->category_id;
    
        // 3. Xử lý Ảnh chính (Nếu có upload ảnh mới)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($game->image && Storage::disk('public')->exists($game->image)) {
                Storage::disk('public')->delete($game->image);
            }
            // Lưu ảnh mới vào cấu trúc thư mục theo slug
            $fileName = 'thumbnail-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $game->image = $request->file('image')->storeAs("games/$slug", $fileName, 'public');
        }
    
        // 4. Xử lý Screenshots (Nếu có upload bộ ảnh mới)
        if ($request->hasFile('screenshots')) {
            // Xóa toàn bộ đống ảnh cũ trong mảng screenshots
            if ($game->screenshots) {
                foreach ($game->screenshots as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            // Lưu bộ ảnh mới
            $imagePaths = [];
            foreach ($request->file('screenshots') as $index => $file) {
                $fileName = 'screenshot-' . ($index + 1) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imagePaths[] = $file->storeAs("games/$slug/screenshots", $fileName, 'public');
            }
            $game->screenshots = $imagePaths;
        }
    
        $game->save();
    
        return redirect()->route('admin.games.index')->with('success', 'Cập nhật thông tin game thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = \App\Models\Game::findOrFail($id);
        $game->delete(); // Laravel sẽ tự điền vào cột deleted_at

        return redirect()->route('admin.games.index')->with('success', 'Đã chuyển game vào thùng rác!');
    }

    // Xem danh sách đã xóa mềm
    public function trash()
    {
        $games = \App\Models\Game::onlyTrashed()->with('category')->latest()->paginate(10);
        return view('admin.games.trash', compact('games'));
    }

    // Khôi phục game
    public function restore($id)
    {
        $game = \App\Models\Game::withTrashed()->findOrFail($id);
        $game->restore();

        return redirect()->route('admin.games.trash')->with('success', 'Khôi phục game thành công!');
    }

    // Xóa vĩnh viễn (Xóa thật trong DB và xóa luôn ảnh vật lý)
    public function forceDelete($id)
    {
        $game = \App\Models\Game::withTrashed()->findOrFail($id);

        // Xóa ảnh chính
        if ($game->image) {
            \Storage::disk('public')->delete($game->image);
        }
        // Xóa bộ ảnh screenshots
        if ($game->screenshots) {
            foreach ($game->screenshots as $path) {
                \Storage::disk('public')->delete($path);
            }
        }

        $game->forceDelete(); // Xóa sạch dấu vết trong DB

        return redirect()->route('admin.games.trash')->with('success', 'Đã xóa vĩnh viễn game và dữ liệu hình ảnh!');
    }

    public function toggleStatus($id)
    {
        // Sử dụng find thay vì findOrFail để mình tự bắt lỗi và trả về JSON chuẩn
        $game = \App\Models\Game::find($id);

        if (!$game) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Không tìm thấy sản phẩm game!'
            ], 404);
        }

        // Đảo ngược trạng thái: Đang 1 thành 0, đang 0 thành 1
        $game->is_active = !$game->is_active;
        $game->save();

        // Trả về JSON theo đúng mẫu chuyên nghiệp
        return response()->json([
            'status' => 'success',
            'message' => $game->is_active ? 'Đã hiển thị sản phẩm game!' : 'Đã ẩn sản phẩm game!',
            'new_state' => $game->is_active
        ]);
    }

    // Bật/tắt Game Hot (Nổi bật)
    public function toggleFeatured($id)
    {
        $game = \App\Models\Game::find($id);

        if (!$game) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm game!'], 404);
        }

        // Đảo ngược trạng thái Hot
        $game->is_featured = !$game->is_featured;
        $game->save();

        return response()->json([
            'status' => 'success',
            'message' => $game->is_featured ? 'Đã thêm vào danh sách Game Hot!' : 'Đã gỡ khỏi danh sách Game Hot!',
            'new_state' => $game->is_featured
        ]);
    }
}
