<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Api\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\EattingController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\ProvincesController;
use App\Http\Controllers\Api\RechargeController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoteController;
use App\Models\Restaurant;
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
    Route::group(['prefix' => "admin"], function () {
        Route::apiResource("user", AdminUserController::class);
        Route::post("user/band/{id}", [AdminUserController::class, "band"]);
        Route::post("user/active/{id}", [AdminUserController::class, "active"]);
        Route::post("user/set-user/{id}", [AdminUserController::class, "setUser"]);
        Route::post("user/set-vendor/{id}", [AdminUserController::class, "setVendor"]);
        Route::post("/blog/status/{blog}", [AdminBlogController::class, "status"]);
        Route::apiResource("/restaurant", AdminRestaurantController::class);
        Route::get("/order-by-date/{date}", [OrderController::class, "order_by_date_v"]);
    });
});


Route::apiResource("user", UserController::class)->middleware('auth:sanctum');
Route::post("user-info", [UserController::class, "info"])->middleware('auth:sanctum');
Route::post("user-vendor", [UserController::class, "vendor"])->middleware('auth:sanctum');
Route::post("login", [UserController::class, "login"]);
Route::post("register", [UserController::class, "register"]);
Route::get("logout", [UserController::class, "logout"])->middleware('auth:sanctum');
Route::get("403", function () {
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

Route::apiResource("/restaurant", RestaurantController::class, ["only" => ["show", "index"]]);
Route::apiResource("/restaurant", RestaurantController::class, ["except" => ["show", "index"]])->middleware("auth:sanctum");
Route::post("/restaurant/update", [RestaurantController::class, "update"])->middleware("auth:sanctum");
Route::get("/my-restaurant", [RestaurantController::class, "restaurantUser"])->middleware("auth:sanctum");

Route::get("/eating/top-discount", [EattingController::class, "topDiscount"]);
Route::apiResource("/eating", EattingController::class, ["only" => ["index"]]);
Route::apiResource("/eating", EattingController::class, ["except" => ["index", "topDiscount"]])->middleware("auth:sanctum");
Route::get("/eating/ofres/{id}", [EattingController::class, "eatingforres"])->middleware("auth:sanctum");
Route::post("/eating/update", [EattingController::class, "update"])->middleware("auth:sanctum");


Route::apiResource("/tableinfo", TableController::class)->middleware("auth:sanctum");
Route::post("/tableinfo/update", [TableController::class, "update"])->middleware("auth:sanctum");
Route::get("/tableinfo/ofres/{id}", [TableController::class, "tableforres"])->middleware("auth:sanctum");

Route::delete("/image/{id}", [ImageController::class, "destroy"])->middleware("auth:sanctum");

Route::apiResource("/address", AddressController::class);
Route::apiResource("/province", ProvincesController::class);

Route::apiResource("/vote", VoteController::class)->middleware("auth:sanctum");

Route::apiResource("/order", OrderController::class)->middleware("auth:sanctum");
Route::get("/order-ofres", [OrderController::class, "orderforres"])->middleware("auth:sanctum");
Route::post("/order/cancel/{id}", [OrderController::class, "cancel"])->middleware("auth:sanctum");
Route::post("/order/active/{id}", [OrderController::class, "active"])->middleware("auth:sanctum");
Route::post("/order/done/{id}", [OrderController::class, "done"])->middleware("auth:sanctum");

Route::apiResource("/order-detail", OrderDetailController::class)->middleware("auth:sanctum");

Route::get("/search/{key}", [RestaurantController::class, "search"]);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => "recharge"], function () {
        Route::get("/", [RechargeController::class, "getAll"]);
        Route::post("momo-qr", [RechargeController::class, "momoQR"]);
        Route::get("check", [RechargeController::class, "check"]);
    });
});
