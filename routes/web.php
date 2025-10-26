<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MutationItemRequestController;
use App\Http\Controllers\MaintenanceItemRequestController;
use App\Http\Controllers\RemoveItemRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('types', TypeController::class);
    Route::resource('users', UserController::class);
    Route::resource('item-requests', ItemRequestController::class);
    Route::resource('items', ItemController::class);
    Route::resource('mutation-item-requests', MutationItemRequestController::class);
    Route::resource('maintenance-item-requests', MaintenanceItemRequestController::class);
    Route::resource('remove-item-requests', RemoveItemRequestController::class);
});
