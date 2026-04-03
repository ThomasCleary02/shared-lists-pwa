<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListItemController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'shared_lists' => Auth::user()->listsOwned()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::get('/lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('/lists', [ListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{shared_list}', [ListController::class, 'show'])->name('lists.show');
    Route::delete('/lists/{shared_list}', [ListController::class, 'destroy'])->name('lists.destroy');

    Route::post('/lists/{shared_list}/items', [ListItemController::class, 'store'])->name('list_items.store');
    Route::patch('/list_items/{list_item}', [ListItemController::class, 'update'])->name('list_items.update');
    Route::delete('/list_items/{list_item}', [ListItemController::class, 'destroy'])->name('list_items.destroy');
});

require __DIR__.'/auth.php';
