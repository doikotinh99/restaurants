<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/order",
     *     operationId="orderOfUser",
     *     tags={"Order"},
     *     summary="All order of User",
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
        $order = Order::all();
        foreach($order as $vals){
            foreach ($vals->orderDetail as $val) {
                $val->eating;
            }
            $vals->user;
            $vals->restaurant;
            $vals->table;
        }
        return $order;
    }

    /**
     * @OA\Post(
     *     path="/api/order",
     *     operationId="Addorder",
     *     tags={"Order"},
     *     summary="Add order",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="restaurant_id",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="table_id",
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
        //
        $order = Order::create([
            "user_id" => $request->user()->id,
            "restaurant_id" => $request->restaurant_id,
            "table_id" => $request->table_id,
            "status" => 0
        ]);
        return $order;
    }

    /**
     * @OA\get(
     *     path="/api/order/{id}",
     *     operationId="Showorder",
     *     tags={"Order"},
     *     summary="detailt order",
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
        $order = Order::where("id", $id)->first();
        foreach ($order->orderDetail as $val) {
            $val->eating;
        }
        $order->user;
        $order->restaurant;
        $order->table;
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

    public function cancel(Order $order){
        $order->update([
            "status" => "false"
        ]);
        return $order;
    }
}
