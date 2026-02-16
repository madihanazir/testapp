<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::middleware(['web','auth'])->group(function () {

    Route::get('/users', [UserController::class, 'index']);

    Route::middleware('can:admin')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
    });

});
