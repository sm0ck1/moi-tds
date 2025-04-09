<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\HelperController;
use App\Http\Controllers\Domain\DomainController;
use App\Http\Controllers\Portal\PortalPlacementController;
use Illuminate\Support\Facades\Route;

Route::get('/short-url', [HelperController::class, 'getShortUrl'])->name('short-url');

Route::get('get-portal-placements-with-domain', [ApiController::class, 'getPortalPlacementsWithDomain'])->name('get-portal-placements-with-domain');
Route::get('get-portal-placements', [ApiController::class, 'getPortalPlacements'])->name('get-portal-placements');
Route::get('get-portal-placements/{portalPlacement}', [ApiController::class, 'getPortalPlacementsOnlyForId']);
Route::post('send-google-ping', [ApiController::class, 'sendLinkToGoogle']);
Route::post('add-new-links-to-ping', [ApiController::class, 'addNewLinksToPing'])->name('add-new-links-to-ping');
Route::post('set-success-ping', [ApiController::class, 'setSuccessPing'])->name('set-success-ping');
Route::patch('set-in-search/{portalPlacement}', [PortalPlacementController::class, 'inSearch'])->name('api-portal-placements.in_search');
Route::patch('ping-again/{portalPlacement}', [PortalPlacementController::class, 'pingAgain'])->name('api-portal-placements.ping-again');
Route::patch('domains/checkboxes/{domain}', [DomainController::class, 'editCheckboxes'])->name('api-domain.edit');
