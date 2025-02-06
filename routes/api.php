<?php

use App\Http\Controllers\Api\HelperController;
use App\Http\Controllers\RedirectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/short-url', [HelperController::class,'getShortUrl'])->name('short-url');
