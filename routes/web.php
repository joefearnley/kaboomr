<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\BookmarkTagController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserAccountAdminController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('bookmarks', BookmarkController::class)->middleware('auth');

Route::get('bookmarks/tag/{tag}', [BookmarkTagController::class, 'list'])->middleware('auth');

Route::get('bookmarks/search/{term}', [SearchController::class, 'index'])->middleware('auth');

Route::group(['prefix' => 'account', 'middleware' => ['auth']], function() {
    Route::get('/', [UserAccountController::class, 'index'])
        ->name('account');

    Route::patch('/update-name', [UserAccountController::class, 'updateName'])
        ->name('account.update-name');

    Route::patch('/update-email', [UserAccountController::class, 'updateEmail'])
        ->name('account.update-email');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    Route::get('/', [AdminController::class, 'index'])
        ->name('admin.index');

    Route::resource('accounts', UserAccountAdminController::class);
});