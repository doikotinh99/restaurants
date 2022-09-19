<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recharge;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/recharge",
     *     tags={"recharge"},
     *     summary="all recharge is user",
     *     operationId="rechargeAll",
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function getAll(){
        $result = Recharge::where("user_id", Auth::user()->id)->get();
        foreach($result as $val){
            $val->transaction;
        }
        return response()->json(["recharge" => $result], 200);
    }

    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    /**
     * @OA\Post(
     *     path="/api/recharge/momo-qr",
     *     tags={"recharge"},
     *     summary="add recharge",
     *     operationId="recharge",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="amount",
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
    public function momoQR(Request $request)
    {
        $endpoint = env("ENDPOINT");
        $partnerCode = env("PARTNERCODE");
        $accessKey = env("ACCESSKEY");
        $secretKey = env("SECRETKEY");
        $orderInfo = "Nạp coin dtcl";
        $amount = $request->amount;
        $orderId = "ORDER" . time() ."";
        $redirectUrl = env("APP_PORT")."/api/recharge/check";
        $ipnUrl = env("APP_PORT")."/api/recharge/check";
        $extraData = "";
        $requestId = "REQUEST" . time() . "";
        $requestType = "captureWallet";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
    //    dd($jsonResult);
        error_log(print_r($jsonResult, true));
        return $jsonResult;
    }

    public function check(Request $request)
    {
        // dd($request);
       if($request->resultCode == 0)
       {
            $code = Recharge::where("code", $request->orderId)->first();
            if($code) return response()->json(["msg" => "If you continue, your account will be locked"], 201);
            $recharge = Recharge::create([
                "user_id" => $request->user()->id,
                "code" => $request->orderId,
                'type' => $request->orderType
            ]);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date = date('Y-m-d H:i:s');
            $result = $recharge->transaction()->save($transaction);
            return response()->json(["transaction" => $result], 200);
       }
    }
}
