<?php

use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'Login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('add-inventories', [InventoryController::class, 'store']);
    Route::patch('update-inventories/{id}', [InventoryController::class, 'update']);
    Route::delete('delete-inventories/{id}', [InventoryController::class, 'destroy']);
    Route::get('inventory-all', [InventoryController::class, 'index']);
    Route::get('inventory-id/{id}', [InventoryController::class, 'show']);
});
