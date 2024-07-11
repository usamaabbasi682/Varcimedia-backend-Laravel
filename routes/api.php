<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    

    Route::controller(DashboardController::class)->group(function(){
        Route::get('/dashboard','index');
    });
    
    Route::delete('file/{id}/remove',[ProjectController::class,'removeFile']);
    Route::get('my-projects',[ProjectController::class,'myProject']);
    Route::put('my-profile',[ProfileController::class,'update']);
    Route::get('my-profile/{id}',[ProfileController::class,'edit']);

    Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function(){
        Route::get('/load/{sender_id}/{receiver_id}/{project_id}','index');
        Route::post('/save-message','store');
    });

    Route::apiResource('/projects',ProjectController::class);

    Route::middleware(['role:admin'])->group(function () {
        Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function() {
            Route::get('/admin','admins');
            Route::get('/client','clients');
            Route::get('/writer','writers');
            Route::get('/editor','editors');
        });
        Route::apiResource('/users',UserController::class);
    });
});