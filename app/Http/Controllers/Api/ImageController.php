<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
/**
     * @OA\Delete(
     *     path="/api/image/{id}",
     *     operationId="deleteImage",
     *     tags={"Image"},
     *     summary="Delete Image",
     *     @OA\Parameter(
     *         name="id",
     *         description="Image id",
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
    public function destroy(Image $image)
    {
        $image->delete();
        return response()->json(["images" => "deleted"], 200);
    }
}
