<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequests;
use App\Models\User;
use App\Models\UserInfor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/user",
     *     tags={"Account"},
     *     summary="Get all user",
     *     operationId="someRoute",
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        return response()->json(["msg" => "false method"], 404);
    }
    /**
     * @OA\Get(
     *     path="/api/admin/user/{id}",
     *     tags={"Account"},
     *     summary="Get user",
     *     operationId="isUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="get user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function show($id)
    {
        if (Auth::check()) {
            return response()->json(["datas" => User::find($id)], 200);
        } else {
            return response()->json(['msg' => 'login'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        return response()->json(["msg" => "false method"], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/user/{id}",
     *     operationId="DeleteUser",
     *     tags={"Account"},
     *     summary="Delete user",
     *     description="Delete user",
     *     @OA\Parameter(
     *         name="id",
     *         description="user id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function destroy($id)
    {
        //
        if(Auth::user()->role == 1) {
            $result = User::where("id", $id)->delete();
            if($result) return response()->json(["msg" => "deleted", "id" => $id], 200);
            else return response()->json(["msg" => "false", "id" => "User does not exist"], 200);
        }
        return response()->json(["msg" => "login"], 403);
    }
    /**
     * @OA\Post(
     *     path="/api/admin/user/band/{id}",
     *     operationId="BandUser",
     *     tags={"Account"},
     *     summary="Band user",
     *     description="Band user",
     *     @OA\Parameter(
     *         name="id",
     *         description="user id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function band($id)
    {
        $user = User::where("id", $id);
        $status = $user->update(["status" => 0]);
        $user->currentAccessToken()->delete();
        if($status) return response()->json(["msg" => "true", "user" => "banded"], 200);
        return response()->json(["msg" => "false", "user" => $id], 201);
    }
    /**
     * @OA\Post(
     *     path="/api/admin/user/active/{id}",
     *     operationId="ActiveUser",
     *     tags={"Account"},
     *     summary="Active user",
     *     description="Active user",
     *     @OA\Parameter(
     *         name="id",
     *         description="user id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function active($id)
    {
        if(Auth::user()->role !== 1) response()->json(["msg" => "false"], 403);
        $status = User::where("id", $id)->update(["status" => 1]);
        if($status) return response()->json(["msg" => "true", "user" => "unband"], 200);
        return response()->json(["msg" => "false", "user" => $id], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/user/set-vendor/{id}",
     *     operationId="setVendor",
     *     tags={"Account"},
     *     summary="Active user",
     *     description="set vendor",
     *     @OA\Parameter(
     *         name="id",
     *         description="user id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function setVendor($id)
    {
        if(Auth::user()->role !== 1) response()->json(["msg" => "false"], 403);
        $status = User::where("id", $id)->update(["role" => 2]);
        if($status) return response()->json(["msg" => "true", "user" => "vendor"], 200);
        return response()->json(["msg" => "false", "user" => $id], 201);
    }
    /**
     * @OA\Post(
     *     path="/api/admin/user/set-user/{id}",
     *     operationId="setUser",
     *     tags={"Account"},
     *     summary="set user",
     *     description="set user",
     *     @OA\Parameter(
     *         name="id",
     *         description="user id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function setUser($id)
    {
        if(Auth::user()->role !== 1) response()->json(["msg" => "false"], 403);
        $status = User::where("id", $id)->update(["role" => 0]);
        if($status) return response()->json(["msg" => "true", "user" => "User"], 200);
        return response()->json(["msg" => "false", "user" => $id], 201);
    }

    
}
