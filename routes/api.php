<?php

use App\Http\Controllers\Api\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Api\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\RechargeController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::group(['prefix' => "admin"], function(){
        Route::apiResource("user", AdminUserController::class);
        Route::post("user/band/{id}", [AdminUserController::class, "band"]);
        Route::post("user/active/{id}", [AdminUserController::class, "active"]);
        Route::post("/blog/status/{blog}", [AdminBlogController::class, "status"]);
        Route::apiResource("/restaurant", AdminRestaurantController::class);
    });
});


Route::apiResource("user", UserController::class)->middleware('auth:sanctum');
Route::post("login", [UserController::class, "login"]);
Route::post("register", [UserController::class, "register"]);
Route::get("logout", [UserController::class, "logout"])->middleware('auth:sanctum');
Route::get("403", function(){
    return response()->json(["msg" => "login"], 403);
})->name("403");

Route::apiResource("blog", BlogController::class, ["only" => [
    "index", "show"
]]);

Route::apiResource("blog", BlogController::class, ["only" => [
    "store", "destroy", "update"
]])->middleware("auth:sanctum");

Route::post("/blog/cmt", [BlogController::class, "cmt"])->middleware("auth:sanctum");
Route::post("/blog/repcmt", [BlogController::class, "replyComment"])->middleware("auth:sanctum");
Route::post("blog/images", [BlogController::class, "pushImages"]);

Route::apiResource("/restaurant", RestaurantController::class)->middleware("auth:sanctum");

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => "recharge"], function(){
        Route::get("/", [RechargeController::class, "getAll"]);
        Route::post("momo-qr", [RechargeController::class, "momoQR"]);
        Route::get("check", [RechargeController::class, "check"]);
    });
});