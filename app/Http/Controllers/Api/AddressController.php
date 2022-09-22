<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/address",
     *     operationId="address",
     *     tags={"Address"},
     *     summary="All address",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function index()
    {
        $result = Address::all();
        foreach($result as $val){
            $val->isCity;
            $val->isDistrict;
            $val->isWards;
        }
        return $result;
    }

    /**
     * @OA\Post(
     *     path="/api/address",
     *     tags={"Address"},
     *     summary="add address",
     *     operationId="addAddress",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="city",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="district",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="wards",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="detail",
     *                  type="text"
     *               ),
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
        $address = Address::create([
            "city" => $request->city,
            "district" => $request->district,
            "wards" => $request->wards,
            "detail" => $request->detail,
        ]);

        return $address;
    }

    /**
     * @OA\get(
     *      path="/api/address/{id}",
     *     tags={"Address"},
     *     summary="show address",
     *     operationId="showAddress",
     *     @OA\Parameter(
     *         name="id",
     *         description="address id",
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
     * 
     * )
     */
    public function show($id)
    {
        $result = Address::where("id", $id)->first();
        if(!$result) return response()->json(["msg" => null], 200);
        $result->isCity;
        $result->isDistrict;
        $result->isWards;
        return $result;
    }

    /**
     * @OA\Put(
     *     path="/api/address/{id}",
     *     tags={"Address"},
     *     summary="edit address",
     *     operationId="editAddress",
     *     @OA\Parameter(
     *         name="id",
     *         description="address id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="city",
     *         description="city id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="district",
     *         description="district id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="wards",
     *         description="wards id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="detail",
     *         description="detail",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function update(Request $request, $id)
    {
        $address = Address::find($id)->first();
        $result = $address->update([
            "city" => $request->city,
            "district" => $request->district,
            "wards" => $request->wards,
            "detail" => $request->detail,
        ]);

        return $result;
    }

    /**
     * @OA\Delete(
     *     path="/api/address/{id}",
     *     tags={"Address"},
     *     summary="delete Address",
     *     operationId="deleteAddress",
     *     @OA\Parameter(
     *         name="id",
     *         description="Address id",
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
    public function destroy(Address $address)
    {
        //
        $address->delete();
        return response()->json(["msg" => "deleted"], 200);
    }
}
