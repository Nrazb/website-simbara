<?php

use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemRequestApiController;
use App\Http\Controllers\Api\TypeApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\MutationItemRequestApiController;
use App\Http\Controllers\Api\MaintenanceItemRequestApiController;
use App\Http\Controllers\Api\RemoveItemRequestApiController;
use App\Http\Controllers\AuthController;

Route::name("api.")->group(function() {
    Route::post('/login', [AuthController::class, 'apiLogin']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('users', UserApiController::class);
        Route::resource('item-requests', ItemRequestApiController::class);
        Route::resource('types', TypeApiController::class);
        Route::resource('items', ItemApiController::class);
        Route::resource('mutation-item-requests', MutationItemRequestApiController::class);
        Route::resource('maintenance-item-requests', MaintenanceItemRequestApiController::class);
        Route::resource('remove-item-requests', RemoveItemRequestApiController::class);
    });
});