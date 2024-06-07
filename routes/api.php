<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/users',UserController::class);
    Route::apiResource('/projects',ProjectController::class);
});