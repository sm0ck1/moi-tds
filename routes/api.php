<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\HelperController;
use Illuminate\Support\Facades\Route;


Route::get('/short-url', [HelperController::class, 'getShortUrl'])->name('short-url');
Route::get('get-portal-placements', [ApiController::class, 'getPortalPlacements'])->name('get-portal-placements');
Route::post('set-success-ping', [ApiController::class, 'setSuccessPing'])->name('set-success-ping');
Route::patch('set-in-search/{portalPlacement}', [\App\Http\Controllers\Portal\PortalPlacementController::class, 'inSearch'])->name('api-portal-placements.in_search');

