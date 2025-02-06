<?php

use App\Http\Controllers\Domain\DomainController;
use App\Http\Controllers\Partner\PartnerController;
use App\Http\Controllers\Partner\PartnerLinksController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Portal\PortalPartnerLinksController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\Topic\TopicController;
use App\Http\Controllers\VisitUsers\VisitUsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::withoutMiddleware(['web'])->get('r/{short_url}', [RedirectController::class, 'redirect']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->group(function () {

    Route::resource('partners', PartnerController::class);
    Route::resource('topic', TopicController::class);
    Route::resource('portal', PortalController::class);
    Route::resource('domain', DomainController::class);
    Route::resource('partner-links', PartnerLinksController::class);
    Route::get('visits', [VisitUsersController::class, 'getVisits'])->name('visits.index');
    Route::post('portal-partner-links', [PortalPartnerLinksController::class, 'storePortalPartnerLinks'])->name('portal-partner-links.store');

//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
