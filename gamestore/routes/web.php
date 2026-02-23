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
