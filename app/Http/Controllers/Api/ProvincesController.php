<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address_city;
use App\Models\Address_district;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProvincesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/province",
     *     operationId="province",
     *    tags={"Province"},
     *     summary="All province",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function index()
    {
        $province = Address_city::all();
        foreach($province as $val){
            $val->district;
            foreach($val->district as $distr){
                $distr->ward;
            }
        }
        return $province;
    }

    /**
     * @OA\post(
     *     path="/api/province",
     *     operationId="addProvince",
     *    tags={"Province"},
     *     summary="add province",
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * 
     * )
     */
    public function store(Request $request)
    {
        $response = Http::get('https://provinces.open-api.vn/api/?depth=3')->json();
        foreach($response as $c){
            $city = Address_city::create([
                "name" => $c["name"]
            ]);
            foreach($c["districts"] as $distr){
                $district = Address_district::create([
                    "name" => $distr["name"],
                    "city_id" => $city->id
                ]);
                foreach($distr["wards"] as $ward){
                    $w = Ward::create([
                        "name" => $ward["name"],
                        "district_id" => $district->id
                    ]);
                }
            }
        }
        return response()->json(["data" => $response[0]], 200);
    }

    /**
     * @OA\get(
     *     path="/api/province/{id}",
     *     operationId="ShowProvince",
     *    tags={"Province"},
     *     summary="detailt Province",
     *     @OA\Parameter(
     *         name="id",
     *         description="Province id",
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
        $result = Address_city::where("id", $id)->get();
        foreach($result as $val){
            $val->district;
            foreach($val->district as $distr){
                $distr->ward;
            }
        }
        return $result;
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
