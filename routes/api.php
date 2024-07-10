<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("/categories")->group(function () {
    Route::get("/", [CategoryController::class, "getAllCategories"]);
    Route::get("/{id}", [CategoryController::class, "getCategoryById"]);
    Route::post("/", [CategoryController::class, "addCategory"])->middleware("isItAdmin");
    Route::delete("/{id}", [CategoryController::class, "deleteCategory"])->middleware("isItAdmin");
    Route::put("/edit/{id}", [CategoryController::class, "editCategory"])->middleware("isItAdmin");
});

Route::prefix("/auth")->group(function(){
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/edituser", [AuthController::class, "editUser"])->middleware("verifyToken");
});
