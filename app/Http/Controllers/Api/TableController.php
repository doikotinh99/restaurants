<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\TableInfo;
use Illuminate\Http\Request;

class TableController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/tableinfo",
     *     operationId="TableInfo",
     *     tags={"TableInfo"},
     *     summary="All TableInfo",
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
        $result = TableInfo::all();

        foreach ($result as $val) {
            $val->images;
        }

        return response()->json(["TableInfo" => $result], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/tableinfo",
     *     operationId="AddTableInfo",
     *     tags={"TableInfo"},
     *     summary="Add TableInfo",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="restaurant_id",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="type",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="chair",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="count",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="description",
     *                  type="text"
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
        // return $request->all();
        //
        $table = TableInfo::create([
            "type" => $request->type,
            "chair" => $request->chair,
            "count" => $request->count,
            "description" => $request->description,
            "restaurant_id" => $request->restaurant_id,
            "status" => 1
        ]);

        // if ($request->image) {
        //     $files = $request->image;
        //     foreach ($files as $key => $file) {
        //         // $images = $file->store("public/tables");
        //         // // $filename = $images->name;
        //         // $filename = explode("/", $images)[2];
        //         // $image = new Image([
        //         //     "path" => "tables/" . $filename
        //         // ]);
        //         $ext = $file->extension();
        //         $fileName = 'tables-' . time() . '.' . $ext;
        //         $file->move(public_path("images/tables"), $fileName);
        //         $image = new Image([
        //             "path" => "tables/" . $fileName
        //         ]);
        //         $table->images()->save($image);
        //     }
        // }
        return response()->json(["tables" => $table], 200);
    }

    /**
     * @OA\get(
     *     path="/api/tableinfo/{id}",
     *     operationId="ShowTableInfo",
     *     tags={"TableInfo"},
     *     summary="detailt TableInfo",
     *     @OA\Parameter(
     *         name="id",
     *         description="TableInfo id",
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
        $result = TableInfo::where("id", $id)->get();
        if(isset($result->images)){
            $result->images;
        }
        return response()->json(["TableInfo" => $result], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/tableinfo/update",
     *     operationId="editTableInfo",
     *     tags={"TableInfo"},
     *     summary="Edit TableInfo",
     *          @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *           @OA\Property(
     *                  property="id",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="type",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="chair",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="count",
     *                  type="time"
     *               ),
     *              @OA\Property(
     *                  property="description",
     *                  type="text"
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
        $tableInfo = TableInfo::find($request->id)->first();
        $result = $tableInfo->update([
            "type" => $request->type,
            "chair" => $request->chair,
            "count" => $request->count,
            "description" => $request->description
        ]);

        // if ($request->image) {
        //     $files = $request->image;
        //     foreach ($files as $key => $file) {
        //         // $images = $file->store("public/tables");
        //         // // $filename = $images->name;
        //         // $filename = explode("/", $images)[2];
        //         $ext = $file->extension();
        //         $fileName = 'tables-' . time() . '.' . $ext;
        //         $file->move(public_path("images/tables"), $fileName);
        //         $image = new Image([
        //             "path" => "tables/" . $fileName
        //         ]);
        //         $tableInfo->images()->save($image);
        //     }
        // }
        return response()->json(["restaurant" => $result], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tableinfo/{id}",
     *     operationId="deletetableInfo",
     *     tags={"TableInfo"},
     *     summary="Delete tableInfo",
     *     @OA\Parameter(
     *         name="id",
     *         description="tableInfo id",
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
    public function destroy(TableInfo $tableInfo)
    {
        $tableInfo->delete();
        $tableInfo->images()->delete();
        return response()->json(["tableInfo" => "deleted"], 200);
    }

    public function Tableforres($id)
    {
        //
        $result = TableInfo::where("restaurant_id", $id)->get();

        foreach ($result as $val) {
            $val->images;
            $val->restaurant;
        }

        return $result;
    }
}
