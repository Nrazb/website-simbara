<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TypeApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ItemRequestApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\MutationItemRequestApiController;
use App\Http\Controllers\Api\MaintenanceItemRequestApiController;
use App\Http\Controllers\Api\RemoveItemRequestApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ReportsApiController;

Route::name('api.')->group(function () {
    Route::post('/login', [AuthApiController::class, 'apiLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [DashboardApiController::class, 'index'])->name('dashboard');

        Route::get('/types', [TypeApiController::class, 'index'])->name('types.index');
        Route::post('/types', [TypeApiController::class, 'store'])->name('types.store');
        Route::put('/types/{id}', [TypeApiController::class, 'update'])->name('types.update');
        Route::delete('/types/{id}', [TypeApiController::class, 'destroy'])->name('types.destroy');

        Route::get('/users', [UserApiController::class, 'index'])->name('users.index');
        Route::post('/users', [UserApiController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserApiController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserApiController::class, 'destroy'])->name('users.destroy');

        Route::get('/item-requests', [ItemRequestApiController::class, 'index'])->name('item-requests.index');
        Route::post('/item-requests', [ItemRequestApiController::class, 'store'])->name('item-requests.store');
        Route::put('/item-requests/{id}', [ItemRequestApiController::class, 'update'])->name('item-requests.update');
        Route::delete('/item-requests/{id}', [ItemRequestApiController::class, 'destroy'])->name('item-requests.destroy');
        Route::patch('/item-requests/{id}/send', [ItemRequestApiController::class, 'send'])->name('item-requests.send');

        Route::get('/items', [ItemApiController::class, 'index'])->name('items.index');
        Route::get('/items/create', [ItemApiController::class, 'create'])->name('items.create');
        Route::post('/items/import', [ItemApiController::class, 'import'])->name('items.import');
        Route::post('/items/{itemRequest:id}', [ItemApiController::class, 'store'])->name('items.store');

        Route::get('/mutation-item-requests', [MutationItemRequestApiController::class, 'index'])->name('mutation-item-requests.index');
        Route::post('/mutation-item-requests', [MutationItemRequestApiController::class, 'store'])->name('mutation-item-requests.store');
        Route::post('/mutation-item-requests/{mutationItemRequest}/confirm', [MutationItemRequestApiController::class, 'confirm'])->name('mutation-item-requests.confirm');

        Route::get('/maintenance-item-requests', [MaintenanceItemRequestApiController::class, 'index'])->name('maintenance-item-requests.index');
        Route::post('/maintenance-item-requests', [MaintenanceItemRequestApiController::class, 'store'])->name('maintenance-item-requests.store');
        Route::post('/maintenance-item-requests/{maintenanceItemRequest}/confirm-unit', [MaintenanceItemRequestApiController::class, 'confirmUnit'])->name('maintenance-item-requests.confirm-unit');
        Route::post('/maintenance-item-requests/{maintenanceItemRequest}/update-request-status', [MaintenanceItemRequestApiController::class, 'updateRequestStatus'])->name('maintenance-item-requests.update-request-status');
        Route::post('/maintenance-item-requests/{maintenanceItemRequest}/update-item-status', [MaintenanceItemRequestApiController::class, 'updateItemStatus'])->name('maintenance-item-requests.update-item-status');
        Route::post('/maintenance-item-requests/{maintenanceItemRequest}/update-information', [MaintenanceItemRequestApiController::class, 'updateInformation'])->name('maintenance-item-requests.update-information');

        Route::get('/remove-item-requests', [RemoveItemRequestApiController::class, 'index'])->name('remove-item-requests.index');
        Route::post('/remove-item-requests', [RemoveItemRequestApiController::class, 'store'])->name('remove-item-requests.store');
        Route::post('/remove-item-requests/{removeItemRequest}/confirm-unit', [RemoveItemRequestApiController::class, 'confirmUnit'])->name('remove-item-requests.confirm-unit');
        Route::post('/remove-item-requests/{removeItemRequest}/update-status', [RemoveItemRequestApiController::class, 'updateStatus'])->name('remove-item-requests.update-status');

        Route::get('/reports', [ReportsApiController::class, 'index'])->name('reports.index');
        Route::post('/reports/export', [ReportsApiController::class, 'export'])->name('reports.export');
    });
});
