<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\MomoPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MomoController extends Controller
{
    private $momoPayment;

    public function __construct(MomoPayment $momoPayment)
    {
        $this->momoPayment = $momoPayment;
    }
    public function index()
    {
        return view('momo.create');
    }

    public function store(Request $request)
    {
        $orderInfo = "Thanhtoanquamomo";
        $amount = $request->amount ?? 10000;
        $orderId = time() . "";
        $redirectUrl = route('momo');
        $ipnUrl = route('momo-update-after-payment');
        $extraData = "";
        $requestId = Str::uuid();
        $userId = $request->user_id ?? 5;

        $requestType = $this->momoPayment->getPaymentType($request);
        //before sign HMAC SHA256 signature        
        $requestData = [
            'accessKey' => config('payment.momo.access_key'),
            'amount' => $amount,
            'extraData' => $extraData,
            'ipnUrl' => $ipnUrl,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'partnerCode' => config('payment.momo.partner_code'),
            'redirectUrl' => $redirectUrl,
            'requestId' => $requestId,
            'requestType' => $requestType,

        ];

        $signature = $this->momoPayment->createSignature($requestData);
        $data = array(
            'partnerCode' => config('payment.momo.partner_code'),
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
            'signature' => $signature
        );

        try {
            DB::beginTransaction();
            $result = $this->momoPayment->execPostRequest(json_encode($data));
            $jsonResult = json_decode($result, true);  // decode json
            if ($jsonResult['resultCode'] === 0) {
                $this->momoPayment->savePayment($jsonResult, $userId, $data);
                DB::commit();
                return redirect($jsonResult['payUrl']);
            }
            return redirect()->back()->withErrors('thanh toan failed');
        } catch (\Throwable $th) {
            DB::rollback();
            info($th);
            return redirect()->back()->withErrors($th->getMessage());
        }
    }


    public function showListPayment(Request $request)
    {
        dd($request);
    }

    public function checkTransaction()
    {
        $payments = Payment::all();
        return view('momo.checkTransaction', compact('payments'));
    }

    public function postCheckTransaction(Request $request) 
    {
        // dd($request);

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/query";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $requestId = 1657957621;
        $orderId = 1657957621;
        $rawHash = "accessKey=".$accessKey."&orderId=".$orderId."&partnerCode=".$partnerCode."&requestId=".$requestId;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
        'requestId' => $requestId,
        'orderId' => $orderId,
        'requestType' => "",
        'signature' => $signature,
        'lang' => 'vi');

        $result = MomoPayment::execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json
        dd($jsonResult); // return
    }

    public function updateDataAfterPayment(Request $request)
    {
        info('Update data after payment');
        info($request->all());
        try {
            DB::beginTransaction();
            $this->momoPayment->updatePayment($request->all());
            DB::commit();
        } catch (\Throwable $th) {
            info($th->getMessage());
            DB::rollback();
        }
    }
}
