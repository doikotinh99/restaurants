<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/restaurant",
     *     operationId="RestaurantOfUser",
     *     tags={"Restaurant"},
     *     summary="All Restaurant of User",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     )
     
     *)
     */
    public function index()
    {
        $result = Restaurant::all();

        foreach ($result as $val) {
            $val->user->info;

            foreach ($val->tables as $tables) {
                $tables->images;
            }
            foreach ($val->menu as $eacting) {
                $eacting->images;
            }
            $val->images;
            $val->isAddress->isCity;
            $val->isAddress->isDistrict;
            $val->isAddress->isWards;

            foreach ($val->vote as $vote) {
                $vote->isUser;
            }
            foreach ($val->order as $order) {
                foreach ($order->orderDetail as $val) {
                    $val->eating;
                }
                $order->user;
                $order->restaurant;
                $order->table;
            }
        }

        return response()->json(["restaurant" => $result], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/restaurant",
     *     operationId="AddRestaurant",
     *     tags={"Restaurant"},
     *     summary="Add restaurant",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="name",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="address",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="time_start",
     *                  type="time"
     *               ),
     *              @OA\Property(
     *                  property="time_end",
     *                  type="time"
     *               ),
     *              @OA\Property(
     *                  property="description",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="image[]",
     *                  type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     *               )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function store(Request $request)
    {
        $restaurant = Restaurant::create([
            "user_id" => $request->user()->id,
            "name" => $request->name,
            "address" => $request->address,
            "time_start" => $request->time_start,
            "time_end" => $request->time_end,
            "description" => $request->description,
            "slug" => Str::slug($request->name, "-") . "-" . time()
        ]);

        if ($request->image) {
            $files = $request->image;
            foreach ($files as $key => $file) {
                // $images = $file->store("public/restaurants");
                // // $filename = $images->name;
                // $filename = explode("/", $images)[2];
                $ext = $file->extension();
                $fileName = 'restaurants-' . time() . '.' . $ext;
                $file->move(public_path("images/restaurants"), $fileName);
                $image = new Image([
                    "path" => "restaurants/" . $fileName
                ]);
                $restaurant->images()->save($image);
            }
        }
        return response()->json(["restaurant" => $restaurant], 200);
    }

    /**
     * @OA\get(
     *     path="/api/restaurant/{id}",
     *     operationId="ShowRestaurant",
     *     tags={"Restaurant"},
     *     summary="detailt restaurant",
     *     @OA\Parameter(
     *         name="id",
     *         description="restaurant id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     * 
     * )
     */
    public function show($slug)
    {
        $result = Restaurant::where("slug", $slug)->first();

        $result->user->info;
        foreach ($result->tables as $tables) {
            $tables->images;
        }
        foreach ($result->menu as $eacting) {
            $eacting->images;
        }
        $result->images;
        $result->isAddress->isCity;
        $result->isAddress->isDistrict;
        $result->isAddress->isWards;
        foreach ($result->vote as $vote) {
            $vote->isUser;
        }
        return response()->json(["restaurant" => $result], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/restaurant/update",
     *     operationId="editRestaurant",
     *     tags={"Restaurant"},
     *     summary="Edit restaurant",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="id",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="name",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="address",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="time_start",
     *                  type="time"
     *               ),
     *              @OA\Property(
     *                  property="time_end",
     *                  type="time"
     *               ),
     *              @OA\Property(
     *                  property="description",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="image[]",
     *                  type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     *               )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function update(Request $request)
    {

        $restaurant = Restaurant::find($request->id)->first();
        if ($request->user()->id != $restaurant->user_id)
            return response()->json(["msg" => "false user"], 404);
        $result = $restaurant->update([
            "name" => $request->name,
            "address" => $request->address,
            "time_start" => $request->time_start,
            "time_end" => $request->time_end,
            "description" => $request->description,
            "slug" => Str::slug($request->name, "-") . "-" . time()
        ]);

        if ($request->image) {
            $files = $request->image;
            foreach ($files as $key => $file) {
                // $images = $file->store("public/restaurants");
                // // $filename = $images->name;
                // $filename = explode("/", $images)[2];
                $ext = $file->extension();
                $fileName = 'restaurants-' . time() . '.' . $ext;
                $file->move(public_path("images/restaurants"), $fileName);
                $image = new Image([
                    "path" => "restaurants/" . $fileName
                ]);
                $restaurant->images()->save($image);
            }
        }
        return response()->json(["restaurant" => $result], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/restaurant/{id}",
     *     operationId="deleteRestaurant",
     *     tags={"Restaurant"},
     *     summary="Delete restaurant",
     *     @OA\Parameter(
     *         name="id",
     *         description="restaurant id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::where("id", $id)->first();
        // return $restaurant;
        $restaurant->delete();
        $restaurant->tables()->delete();
        $restaurant->menu()->delete();
        $restaurant->isAddress()->delete();
        $restaurant->images()->delete();
        $restaurant->vote()->delete();
        $restaurant->order()->delete();
        return "deleted";
    }

    public function restaurantUser()
    {
        $result = Restaurant::where("user_id", Auth::user()->id)->get();
        foreach ($result as $val) {
            $val->user;
            foreach ($val->tables as $tables) {
                $tables->images;
            }
            foreach ($val->menu as $eacting) {
                $eacting->images;
            }
            $val->images;
            $val->isAddress->isCity;
            $val->isAddress->isDistrict;
            $val->isAddress->isWards;

            foreach ($val->vote as $vote) {
                $vote->isUser;
            }

            foreach ($val->order as $order) {
                foreach ($order->orderDetail as $val) {
                    $val->eating;
                }
                $order->user;
                $order->restaurant;
                $order->table;
            }
        }
        return $result;
    }

    public function search($key)
    {

        $search = DB::table("searchs")->where("name", "like", "%" . $key . "%")->get();

        return $search;
    }
}
