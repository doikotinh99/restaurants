<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Eating;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EattingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/eating",
     *     operationId="EatingOfUser",
     *     tags={"Eating"},
     *     summary="All Eating of User",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function index()
    {
        //
        $result = Eating::all();

        foreach ($result as $val) {
            $val->images;
            $val->restaurant;
        }

        return response()->json(["eatting" => $result], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/eating",
     *     operationId="AddEating",
     *     tags={"Eating"},
     *     summary="Add Eating",
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
     *                  property="price",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="discount",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="restaurant_id",
     *                  type="text"
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
        //
        $eating = Eating::create([
            "name" => $request->name,
            "price" => $request->price,
            "discount" => $request->discount,
            "restaurant_id" => $request->restaurant_id,
            "description" => $request->description,
            "status" => 1
        ]);

        if ($request->image) {
            $files = $request->image;
            foreach ($files as $key => $file) {
                // $images = $file->store("public/eatings");
                // $filename = $images->name;
                // $filename = explode("/", $images)[2];
                $ext = $file->extension();
                $fileName = 'eatings-' . time() . '.' . $ext;
                $file->move(public_path("images/eatings"), $fileName);
                $image = new Image([
                    "path" => "eatings/" . $fileName
                ]);
                $eating->images()->save($image);
            }
        }
        return response()->json(["eating" => $eating], 200);
    }

    /**
     * @OA\get(
     *     path="/api/eating/{id}",
     *     operationId="ShowEating",
     *     tags={"Eating"},
     *     summary="detailt eating",
     *     @OA\Parameter(
     *         name="id",
     *         description="eating id",
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
     * 
     * )
     */
    public function show($id)
    {
        $result = Eating::where("id", $id)->get();
        if(isset($result->images)){
            $result->images;
        }
        return response()->json(["eatting" => $result], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/eating/update",
     *     operationId="editEating",
     *     tags={"Eating"},
     *     summary="Edit Eating",
     *      @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="id",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="name",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="price",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="discount",
     *                  type="time"
     *               ),
     *              @OA\Property(
     *                  property="restaurant_id",
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
        $eating = Eating::find($request->id)->first();
        if ($request->user()->id != $eating->user_id)
            return response()->json(["msg" => "false user"], 404);
        $result = $eating->update([
            "name" => $request->name,
            "price" => $request->price,
            "discount" => $request->discount,
            "description" => $request->description
        ]);

        if ($request->image) {
            $files = $request->image;
            foreach ($files as $key => $file) {
                $ext = $file->extension();
                $fileName = 'eatings-' . time() . '.' . $ext;
                $file->move(public_path("images/eatings"), $fileName);
                $image = new Image([
                    "path" => "eatings/" . $fileName
                ]);
                $eating->images()->save($image);
            }
        }
        return response()->json(["restaurant" => $result], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/eating/{id}",
     *     operationId="deleteEating",
     *     tags={"Eating"},
     *     summary="Delete Eating",
     *     @OA\Parameter(
     *         name="id",
     *         description="Eating id",
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
    public function destroy(Eating $eating)
    {
        $eating->delete();
        $eating->images()->delete();
        return response()->json(["eating" => "deleted"], 200);
    }

    public function topDiscount(){
        $result = Eating::orderBy('discount', 'desc')
        ->limit(12)
        ->get();

        foreach ($result as $val) {
            $val->images;
            $val->restaurant;
        }

        return $result;
    }

    public function eatingforres($id)
    {
        //
        $result = Eating::where("restaurant_id", $id)->get();

        foreach ($result as $val) {
            $val->images;
            $val->restaurant;
        }

        return $result;
    }
}
