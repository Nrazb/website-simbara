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
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/types', [TypeController::class, 'index'])->name('types.index');
    Route::post('/types', [TypeController::class, 'store'])->name('types.store');
    Route::put('/types/{id}', [TypeController::class, 'update'])->name('types.update');
    Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('types.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/item-requests', [ItemRequestController::class, 'index'])->name('item-requests.index');
    Route::post('/item-requests', [ItemRequestController::class, 'store'])->name('item-requests.store');
    Route::put('/item-requests/{id}', [ItemRequestController::class, 'update'])->name('item-requests.update');
    Route::delete('/item-requests/{id}', [ItemRequestController::class, 'destroy'])->name('item-requests.destroy');

    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/import', [ItemController::class, 'import'])->name('items.import');
    Route::post('/items/{itemRequest:id}', [ItemController::class, 'store'])->name('items.store');

    Route::get('/mutation-item-requests', [MutationItemRequestController::class, 'index'])->name('mutation-item-requests.index');
    Route::get('/mutation-item-requests/create', [MutationItemRequestController::class, 'create'])->name('mutation-item-requests.create');
    Route::post('/mutation-item-requests', [MutationItemRequestController::class, 'store'])->name('mutation-item-requests.store');
    Route::post('/mutation-item-requests/{mutationItemRequest}/confirm', [MutationItemRequestController::class, 'confirm'])->name('mutation-item-requests.confirm');

    Route::get('/maintenance-item-requests', [MaintenanceItemRequestController::class, 'index'])->name('maintenance-item-requests.index');
    Route::post('/maintenance-item-requests/{maintenanceItemRequest}/confirm-unit', [MaintenanceItemRequestController::class, 'confirmUnit'])->name('maintenance-item-requests.confirm-unit');
    Route::post('/maintenance-item-requests/{maintenanceItemRequest}/update-request-status', [MaintenanceItemRequestController::class, 'updateRequestStatus'])->name('maintenance-item-requests.update-request-status');
    Route::post('/maintenance-item-requests/{maintenanceItemRequest}/update-item-status', [MaintenanceItemRequestController::class, 'updateItemStatus'])->name('maintenance-item-requests.update-item-status');

    Route::get('/remove-item-requests', [RemoveItemRequestController::class, 'index'])->name('remove-item-requests.index');
    Route::post('/remove-item-requests', [RemoveItemRequestController::class, 'store'])->name('remove-item-requests.store');
    Route::post('/remove-item-requests/{removeItemRequest}/confirm-unit', [RemoveItemRequestController::class, 'confirmUnit'])->name('remove-item-requests.confirm-unit');
    Route::post('/remove-item-requests/{removeItemRequest}/update-status', [RemoveItemRequestController::class, 'updateStatus'])->name('remove-item-requests.update-status');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
});
