<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        foreach ($order as $vals) {
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
            "status" => 0,
            "arrival_time" => $request->arrival_time
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

    public function cancel($id)
    {
        $order = Order::where("id", $id)->update([
            "status" => "false"
        ]);
        return $order;
    }

    public function active($id)
    {
        $order = Order::where("id", $id)->update([
            "status" => "1"
        ]);
        return $order;
    }

    public function done($id)
    {
        $order = Order::where("id", $id)->update([
            "status" => "2"
        ]);
        return $order;
    }

    public function orderforres()
    {
        $restaurant = Restaurant::where("user_id", Auth::user()->id)->first();
        $id = $restaurant->id;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dS = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
        $dE = date("Y-m-d", mktime(23, 59, 59, date("m") + 1, 1 - 1, date("Y")));
        $result = Order::where("restaurant_id", $id)
            ->whereDate('created_at', ">=", $dS)
            ->whereDate('created_at', "<=", $dE)
            ->get();
        for ($i = 0; $i < count($result); $i++) {
            $order_detail = OrderDetail::where("order_id", $result[$i]->id)->get();
            $sum = 0;
            foreach ($order_detail as $od) {
                $sum += $od->quanlity * $od->eating->price;
            }
            $result[$i]->sum = $sum;
        }
        $dS1 = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
        $dE1 = date("Y-m-d", mktime(23, 59, 59, date("m"), 1 - 1, date("Y")));
        $result1 = Order::where("restaurant_id", $id)
            ->whereDate('created_at', ">=", $dS1)
            ->whereDate('created_at', "<=", $dE1)
            ->get();
        for ($i = 0; $i < count($result1); $i++) {
            $order_detail = OrderDetail::where("order_id", $result1[$i]->id)->get();
            $sum = 0;
            foreach ($order_detail as $od) {
                $sum += $od->quanlity * $od->eating->price;
            }
            $result1[$i]->sum = $sum;
        }
        $arr = ["now" => $result, "old" => $result1];
        // foreach ($result as $val) {
        //     foreach ($result->orderDetail as $val) {
        //         $val->eating;
        //     }
        //     $result->user;
        //     $result->restaurant;
        //     $result->table;
        //     return $result;
        // }

        return $arr;
    }

    public function order_by_date_v($date){
        return  DB::table("order_by_date_v") -> where("date", $date) -> get();
        
    }


}