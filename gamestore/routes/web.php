<?php

use Illuminate\Support\Facades\Route;

// Các Controller của trang Khách
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController as UserGameController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ChatbotController;

// Các Controller của trang Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\PostController as AdminPostController;


/*
|--------------------------------------------------------------------------
| 1. KHU VỰC XÁC THỰC (ĐĂNG NHẬP / ĐĂNG KÝ)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


/*
|--------------------------------------------------------------------------
| 2. KHU VỰC KHÁCH HÀNG (PUBLIC ROUTES)
|--------------------------------------------------------------------------
| Các route này ai cũng có thể vào xem được (Khách vãng lai & Đã đăng nhập)
*/
// Trang chủ & Cửa hàng
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kham-pha', [UserGameController::class, 'index'])->name('game');
Route::get('/game/{slug}', [UserGameController::class, 'show'])->name('game.detail');

// Tin tức & Bot AI
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');

// Giỏ hàng
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them/{game_id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/gio-hang/xoa/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Thanh toán & VNPay
Route::post('/thanh-toan', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/thanh-toan/vnpay-return', [CartController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/thanh-toan/thanh-cong', [CartController::class, 'success'])->name('checkout.success');


/*
|--------------------------------------------------------------------------
| 3. KHU VỰC KHÁCH HÀNG VIP (USER ROUTES)
|--------------------------------------------------------------------------
| Bắt buộc phải đăng nhập mới được vào (Gom chung middleware 'auth' cho sạch code)
*/
Route::middleware('auth')->group(function () {
    // Quản lý Hồ sơ cá nhân
    Route::get('/ho-so', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/ho-so/cap-nhat', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/ho-so/doi-mat-khau', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Thư viện Game đã mua
    Route::get('/thu-vien-game', [OrderController::class, 'library'])->name('user.library');

    // Đánh giá siêu phẩm
    Route::post('/game/{id}/review', [ReviewController::class, 'store'])->name('review.store');
});


/*
|--------------------------------------------------------------------------
| 4. KHU VỰC QUẢN TRỊ (ADMIN ROUTES)
|--------------------------------------------------------------------------
| Bắt buộc phải là Admin mới được vào
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Tổng quan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý Đơn hàng
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Quản lý User
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');

    // Quản lý Đánh giá
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Quản lý Bài viết (Tin tức)
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [AdminPostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [AdminPostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [AdminPostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

    // Quản lý Danh mục (Thùng rác & Resource)
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force_delete');
    Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    Route::resource('categories', CategoryController::class);

    // Quản lý Game (Thùng rác & Resource)
    Route::get('games/trash', [AdminGameController::class, 'trash'])->name('games.trash');
    Route::get('games/{id}/restore', [AdminGameController::class, 'restore'])->name('games.restore');
    Route::delete('games/{id}/force-delete', [AdminGameController::class, 'forceDelete'])->name('games.force_delete');
    Route::patch('games/{id}/toggle-status', [AdminGameController::class, 'toggleStatus'])->name('games.toggle');
    Route::patch('games/{id}/toggle-featured', [AdminGameController::class, 'toggleFeatured'])->name('games.toggle_featured');
    Route::resource('games', AdminGameController::class); // Ghi chú: Resource luôn nằm dưới cùng
});