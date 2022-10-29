<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/order-detail",
     *     operationId="OrderDetail",
     *     tags={"Order"},
     *     summary="All order detail of User",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function index()
    {
        $order = OrderDetail::all();
        foreach ($order as $val) {
            $val->eating;
            $val->order;
        }
        return $order;
    }

    /**
     * @OA\Post(
     *     path="/api/order-detail",
     *     operationId="AddOrderDetail",
     *     tags={"Order"},
     *     summary="Add order detail",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="order_id",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="eats",
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
        $eats = $request->eats;
        $eats = json_decode($eats);
        foreach($eats as $eat){
            $order = OrderDetail::create([
                "order_id" => $request->order_id,
                "eating_id" => $eat->id,
                "quanlity" => $eat->quanlity
            ]);
        }
        
        return $order;
    }

    /**
     * @OA\get(
     *     path="/api/order-detail/{id}",
     *     operationId="ShoworderDetail",
     *     tags={"Order"},
     *     summary="detailt order detail",
     *     @OA\Parameter(
     *         name="id",
     *         description="order id",
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
     *     security={{"bearer":{}}},
     * 
     * )
     */
    public function show($id)
    {
        $order = OrderDetail::where("id", $id)->first();
        $order->eating;
        $order->order;
        return $order;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
