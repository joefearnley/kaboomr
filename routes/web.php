<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\BookmarkTagController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserAccountAdminController;

Auth::routes(['verify' => true]);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('bookmarks', BookmarkController::class)
    ->middleware(['auth', 'verified']);

Route::get('bookmarks/tag/{tag}', [BookmarkTagController::class, 'list'])
    ->middleware(['auth','verified'])
    ->name('bookmarks.tag');

Route::get('bookmarks/search/{term}', [SearchController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('bookmarks.search');

Route::group(['prefix' => 'account', 'middleware' => ['auth','verified']], function() {
    Route::get('/', [UserAccountController::class, 'index'])
        ->name('account');

    Route::patch('/update-name', [UserAccountController::class, 'updateName'])
        ->name('account.update-name');

    Route::patch('/update-email', [UserAccountController::class, 'updateEmail'])
        ->name('account.update-email');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'admin']], function() {
    Route::get('/', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('users', UserAccountAdminController::class);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])
    ->name('verification.notice');