<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequests;
use App\Models\User;
use App\Models\UserInfor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Full API Restaurant"
 * )
 *
 * @OA\Tag(
 *     name="Account",
 *     description="full api"
 * )
 *
 *@OAS\SecurityScheme(
 *     securityScheme="bearer",
 *     type="http",
 *     scheme="bearer"
 *)
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"Account"},
     *     summary="Get user",
     *     operationId="isUsers",
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->info) {
            $user->info->isAddress->isCity;
            $user->info->isAddress->isDistrict;
            $user->info->isAddress->isWards;
        }


        return $user;
    }

    public function store(Request $request)
    {
        $user = User::where("id", $request->user()->id)
            ->update([
                "name" => $request->name
            ]);
        return $user;
    }

    public function show($id)
    {
        return response()->json(["msg" => "false method"], 404);
    }
    public function update(Request $request)
    {
        return response()->json(["msg" => "false method"], 404);
    }
    /**
     * @OA\Post(
     *     path="/api/user-info",
     *     operationId="EditUser",
     *     tags={"Account"},
     *     summary="Edit user",
     *     description="Edit user",
     *     @OA\Parameter(
     *         name="phone",
     *         description="Phone number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Your address",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="gender",
     *         description="your gender",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="birday",
     *         description="Phone number (Y-m-d)",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function info(Request $request)
    {
        // return $request->all();
        //
        $user = $request->user();
        $check = $this->checkInfor($user->id);
        if (!$check) {

            $infor = UserInfor::where("user_id", $user->id)->update([
                "phone" => $request->phone,
                "address" => $request->address,
                "gender" => $request->gender,
                "birday" => $request->birday
            ]);
            return $infor;
        }
        $result = UserInfor::create([
            "user_id" => $user->id,
            "phone" => $request->phone,
            "address" => $request->address,
            "gender" => $request->gender,
            "birday" => $request->birday
        ]);
        return $result;
    }
    public function checkInfor($id)
    {
        $result = UserInfor::where("user_id", $id)->first();
        if ($result !== null) {
            return false;
        }
        return true;
    }

    public function destroy($id)
    {
        //
        return response()->json(["msg" => "false method"], 404);
    }
    /**
     * @OA\Get(
     *     path="/api/logout",
     *     operationId="logout",
     *     tags={"Account"},
     *     summary="logout",
     *     description="logout",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json(["msg" => "logout"], 200);
    }



    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"Account"},
     *     summary="login",
     *     description="login",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *               @OA\Property(
     *                  property="password",
     *                  type="password"
     *               )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     )
     *)
     */
    public function login(LoginRequests $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            $user = Auth::user();
            if ($user->status != 1)
                return response()->json(["msg" => "band"], 201);
            $token = $user->createToken('kenny')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], 200);
        }
        return response()->json(['msg' => $validated], 200);
    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     tags={"Account"},
     *     summary="register",
     *     description="register",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="name",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *               @OA\Property(
     *                  property="password",
     *                  type="password"
     *               ),
     *               @OA\Property(
     *                  property="cpassword",
     *                  type="password"
     *               ),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     )
     *)
     */
    public function register(Request $request)
    {
        // return $request->all();
        if ($request->password !== $request->cpassword)
            return ['msg' => 'Confirm password is incorrect'];
        if ($this->checkMail($request->email))
            return ['msg' => 'Email already exists.'];

        $result = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
            'status' => 1,
        ]);
        return response()->json(['msg' => 'success', 'result' => $result], 201);
    }

    private function checkMail($email)
    {
        $result = User::where('email', $email)->first();
        if ($result !== null) {
            return true;
        }
        return false;
    }

    /**
     * @OA\Post(
     *     path="/api/user-vendor",
     *     operationId="RequestVendor",
     *     tags={"Account"},
     *     summary="request is vendor",
     *     description="request is vendor",
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function vendor()
    {
        $status = User::where("id", Auth::user()->id)->update(["role" => 3]);
        return $status;
    }
}
