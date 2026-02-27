<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController as UserGameController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;

// Nhóm Route cho Đăng nhập / Đăng ký
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route cho Đăng ký
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kham-pha', [UserGameController::class, 'index'])->name('game');
Route::get('/game/{slug}', [UserGameController::class, 'show'])->name('game.detail');
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them/{game_id}', [CartController::class, 'add'])->name('cart.add');
// Route xóa game khỏi giỏ (Dùng DELETE cho đúng chuẩn RESTful)
Route::delete('/gio-hang/xoa/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Route xử lý thanh toán chốt đơn
// Route Thanh toán & Nhận kết quả từ VNPay
Route::post('/thanh-toan', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/thanh-toan/vnpay-return', [CartController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/thanh-toan/thanh-cong', [CartController::class, 'success'])->name('checkout.success');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CATEGORIES
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force_delete');
    Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    Route::resource('categories', CategoryController::class);

    // GAMES (Đưa trash lên đầu)
    Route::patch('games/{id}/toggle-status', [AdminGameController::class, 'toggleStatus'])->name('games.toggle');
    Route::patch('games/{id}/toggle-featured', [AdminGameController::class, 'toggleFeatured'])->name('games.toggle_featured');
    Route::get('games/trash', [AdminGameController::class, 'trash'])->name('games.trash');
    Route::get('games/{id}/restore', [AdminGameController::class, 'restore'])->name('games.restore');
    Route::delete('games/{id}/force-delete', [AdminGameController::class, 'forceDelete'])->name('games.force_delete');
    
    // Resource phải nằm cuối cùng trong nhóm games
    Route::resource('games', AdminGameController::class);
});
