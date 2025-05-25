<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NdcSearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Auth routes
require __DIR__. '/auth.php';
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/ndc/search');
    }
    return redirect('/login');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {


    // NDC search routes
    Route::prefix('ndc')->group(function () {
        Route::get('/search', [NdcSearchController::class, 'showSearchForm'])->name('ndc.search');
        Route::post('/search', [NdcSearchController::class, 'handleSearch'])->name('ndc.search.submit');
    // ndc delete
        Route::delete('/{id}', [NdcSearchController::class, 'destroy'])->name('ndc.destroy');

        //saved codes
        Route::get('/saved', [NdcSearchController::class, 'savedResults'])->name('ndc.saved');


    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});