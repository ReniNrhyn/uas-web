<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ExpenditureCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DetailTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management
    Route::get('users/export', [UserController::class, 'exportExcel'])->name('users.export');
    Route::resource('users', UserController::class);

    // Category Menu Management
    Route::resource('category_menus', CategoryController::class);

    // Menu Management
    Route::resource('menus', MenuController::class);

    // Resource Routes untuk Stock
    Route::resource('stocks', StockController::class);

    // Expenditure Category Management
    Route::resource('expenditure-categories', ExpenditureCategoryController::class)->except(['show']);

    // Transaction Routes
    Route::resource('transactions', TransactionController::class)->except(['show']);

    // Detail Transaction Routes
    Route::resource('detail_transactions', DetailTransactionController::class)->except(['show']);

    // Nested Routes untuk Detail Transaction dalam Transaction
    Route::prefix('transactions/{transaction}')->group(function () {
        Route::get('details', [DetailTransactionController::class, 'indexByTransaction'])->name('transactions.details.index');
        Route::get('details/create', [DetailTransactionController::class, 'createForTransaction'])->name('transactions.details.create');
        Route::post('details', [DetailTransactionController::class, 'storeForTransaction'])->name('transactions.details.store');
    });

});

require __DIR__.'/auth.php';
