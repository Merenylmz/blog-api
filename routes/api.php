<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("/categories")->group(function () {
    Route::get("/", [CategoryController::class, "getAllCategories"]);
    Route::get("/{id}", [CategoryController::class, "getCategoryById"]);
    Route::post("/", [CategoryController::class, "addCategory"]);
    Route::delete("/{id}", [CategoryController::class, "deleteCategory"]);
    Route::put("/edit/{id}", [CategoryController::class, "editCategory"]);
});

