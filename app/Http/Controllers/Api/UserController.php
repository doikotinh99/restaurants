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
 *      title="Full API DTCL"
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

    public function index()
    {
        return response()->json(["msg" => "false method"], 404);
    }

    public function store(Request $request)
    {
        return response()->json(["msg" => "false method"], 404);
    }
    
    public function show($id)
    {
        return response()->json(["msg" => "false method"], 404);
    }

    /**
     * @OA\Put(
     *     path="/api/user/{id}",
     *     operationId="EditUser",
     *     tags={"Account"},
     *     summary="Edit user",
     *     description="Edit user",
     *     @OA\Parameter(
     *         name="id",
     *         description="user id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
    public function update(Request $request, $id)
    {
        // return $request->all();
        //
        $user = $request->user();
        if ($id == $user->id) {
            $check = $this->checkInfor($id);
            if (!$check) {
                
                if ($request->phone) UserInfor::where("id", $id)->update(["phone" => $request->phone]);;
                if ($request->address) UserInfor::where("id", $id)->update(["address" => $request->address]);;
                if ($request->gender) UserInfor::where("id", $id)->update(["phogenderne" => $request->gender]);;
                if ($request->birday) UserInfor::where("id", $id)->update(["birday" => $request->birday]);;
                $infor = UserInfor::where("user_id", $id)->first();
                return response()->json(["user_info" => $infor], 200);
            }
            $result = UserInfor::create([
                "user_id" => Auth::user()->id,
                "phone" => $request->phone,
                "address" => $request->address,
                "gender" => $request->gender,
                "birday" => $request->birday
            ]);
            return response()->json(["user_info" => $result], 200);
        }
        return response()->json(["msg" => "login"], 403);
    }

    public function checkInfor($id)
    {
        $result = UserInfor::where("id", $id)->first();
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
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
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
            if($user->status != 1) 
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
            return response()->json(['msg' => 'Confirm password is incorrect'], 400);
        if ($this->checkMail($request->email))
            return response()->json(['msg' => 'Email already exists.'], 400);

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
}
