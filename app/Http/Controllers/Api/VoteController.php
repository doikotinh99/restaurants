<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/vote",
     *     operationId="AllVote",
     *     tags={"Vote"},
     *     summary="All vote",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function index()
    {
        $result = Vote::all();
        foreach($result as $val){
            $val->isUser;
        }
        return $result;
    }

    /**
     * @OA\Post(
     *     path="/api/vote",
     *     operationId="AddVote",
     *     tags={"Vote"},
     *     summary="Add Vote",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="user_id",
     *                  type="bigint"
     *               ),
     *              @OA\Property(
     *                  property="vote",
     *                  type="int"
     *               ),
     *              @OA\Property(
     *                  property="discription",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="restaurant_id",
     *                  type="bigint"
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
        $vote = Vote::create([
            "user_id" => $request->user()->id,
            "vote" => $request->vote,
            "discription" => $request->discription,
            "restaurant_id" => $request->restaurant_id
        ]);
        return $vote;
    }

    /**
     * @OA\get(
     *     path="/api/vote/{id}",
     *     operationId="ShowVote",
     *     tags={"Vote"},
     *     summary="detailt Vote",
     *     @OA\Parameter(
     *         name="id",
     *         description="vote id",
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
    public function show($id)
    {
        $result = Vote::where("id", $id)->first();
        foreach($result as $val){
            $val->isUser;
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
     * @OA\Delete(
     *     path="/api/vote/{id}",
     *     operationId="deleteVote",
     *     tags={"Vote"},
     *     summary="Delete vote",
     *     @OA\Parameter(
     *         name="id",
     *         description="vote id",
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
    public function destroy(Vote $vote)
    {
        $vote->delete();
        return response()->json(["vote" => "deleted"], 200);
    }
}
