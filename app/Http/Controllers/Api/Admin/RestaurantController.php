<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/restaurant",
     *     operationId="Restaurant",
     *     tags={"Restaurant"},
     *     summary="All Restaurant",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function index()
    {
        $result = Restaurant::all();
        
        foreach($result as $val){
            $val->user;
            
            foreach($val->tables as $tables){
                $tables->images;
            }
            foreach($val->menu as $eacting){
                $eacting->images;
            }
            $val->images;
            $val->isAddress;
        }
        
        return response()->json(["restaurant" => $result], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
