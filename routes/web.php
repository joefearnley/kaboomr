<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\BookmarkTagController;
use App\Http\Controllers\AccountController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('bookmarks', BookmarkController::class)->middleware('auth');

Route::get('bookmarks/tag/{tag}', [BookmarkTagController::class, 'list'])->middleware('auth');

Route::get('account', [AccountController::class, 'index'])
    ->name('account')
    ->middleware('auth');

Route::prefix('account')->group(function () {
    Route::patch('/update-name', [AccountController::class, 'updateName'])
        ->name('account.update-name');

    Route::patch('/update-email', [AccountController::class, 'updateEmail'])
        ->name('account.update-email');
});
