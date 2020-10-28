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

Route::get('account', [AccountController::class, 'account.index'])->middleware('auth');
