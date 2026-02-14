<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController as UserGameController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GameController as AdminGameController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kham-pha', [GameController::class, 'index'])->name('game');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force_delete');
    // Route xử lý việc bật tắt (Sử dụng method PATCH vì đây là cập nhật 1 phần)
    Route::patch('/categories/{id}/toggle-status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    Route::resource('categories', CategoryController::class);
    Route::resource('games', AdminGameController::class);
});
