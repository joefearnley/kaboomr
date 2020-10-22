<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookmarkController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('bookmarks', BookmarkController::class)->middleware('auth');
