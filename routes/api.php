<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authentication
Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

// Get all blogs
Route::get("/blogs", [BlogController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post("/logout", [AuthController::class, 'logout']);
    // Get single blog
    Route::get("/blogs/{id}", [BlogController::class, 'show']);

    // Store blog
    Route::post("/blogs", [BlogController::class, 'store']);

    // Update blog
    Route::put("/blogs/{id}", [BlogController::class, 'update']);

    // Delete blog
    Route::delete("/blogs/{id}", [BlogController::class, 'destroy']);

    Route::apiResource("/categories", CategoryController::class);
});
