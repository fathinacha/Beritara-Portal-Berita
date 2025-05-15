<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    $news = App\Models\News::with('category')
        ->where('status', 'published')
        ->latest()
        ->get();
    $categories = App\Models\Category::all();
    return view('welcome', compact('news', 'categories'));
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/test', function () {
        return response()->json([
            'message' => 'Admin access successful!',
            'user' => Auth::user()
        ]);
    });
});

// Category Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('news', NewsController::class);
    Route::get('/news/{news}/preview', [NewsController::class, 'preview'])
        ->name('news.preview')
        ->middleware('auth');
    Route::put('/news/{news}/status', [NewsController::class, 'updateStatus'])
        ->name('news.update.status');
});

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/search', [NewsController::class, 'search'])->name('search');
Route::get('/article/{slug}', [NewsController::class, 'detail'])->name('news.detail');

require __DIR__.'/auth.php';
