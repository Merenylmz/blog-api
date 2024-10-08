<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PolicyController;
use App\Jobs\NewCommentMailJob;
use App\Mail\NewCommentMail;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("/categories")->group(function () {
    Route::get("/", [CategoryController::class, "getAllCategories"]);
    Route::get("/{slug}", [CategoryController::class, "getCategoryBySlug"]);
    // Route::post("/", [CategoryController::class, "addCategory"])->middleware("isItAdmin");
    // Route::delete("/{id}", [CategoryController::class, "deleteCategory"])->middleware("isItAdmin");
    // Route::put("/edit/{id}", [CategoryController::class, "editCategory"])->middleware("isItAdmin");
});

Route::prefix("/auth")->group(function(){
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "register"]);
    Route::get("/logout", [AuthController::class, "logout"]);
    Route::get("/allusers", [AuthController::class, "allUsers"]);
});

Route::prefix("/blogs")->group(function(){
    Route::get("/", [BlogController::class, "getAllBlogs"]);
    Route::get("/popular", [BlogController::class, "getPopularBlogs"]);
    Route::get("/category", [BlogController::class, "getBlogByCategoryId"]);
    Route::get("/{slug}", [BlogController::class, "getBlogBySlug"]);
    // Route::put("/edit/{id}", [BlogController::class, "editBlog"]);
    Route::get("/count/{id}", [BlogController::class, "addViewsCount"]);
    Route::post("/addcomment/{id}", [BlogController::class, "addComments"]);
});
Route::get("/comments", [BlogController::class, "getAllComments"]);

//Policies
Route::get("/policies/{slug}", [PolicyController::class, "getPolicyBySlug"]);

