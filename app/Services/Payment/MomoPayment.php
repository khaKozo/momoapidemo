<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\PaymentDetail;

class MomoPayment
{

    public function initPayment()
    {
    }

    public function execPostRequest($data)
    {
        $url = config('payment.momo.endpoint_url')."/v2/gateway/api/create";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function createSignature(array $data)
    {
        $secretKey = config('payment.momo.secret_key');
        $rawHash = '';

        if (!$secretKey) {
            return false;
        }

        foreach ($data as $key => $value) { 
            $prefix = array_key_first($data) === $key ? '' : '&';
            $rawHash .= $prefix . $key .'='. $value;
        }
        // return $rawHash;
        return hash_hmac('sha256', $rawHash, $secretKey);
    }

    public function getPaymentType($request)
    {
        return config('payment.momo.method_payment')[$request->momo_type] ?? config('payment.momo.method_payment')[1];
    }

    public function savePayment($payment, $userId, $request)
    {
        $paymentModel = new Payment();
        $paymentModel->user_id = $userId;
        $paymentModel->request_id = $payment['requestId'];
        $paymentModel->order_id = $payment['orderId'];
        $paymentSave = $paymentModel->save();

        if ($paymentSave) {
            $paymentDetailModel = new PaymentDetail();
            $paymentDetailModel->payment_id = $paymentModel->id;
            $paymentDetailModel->order_info = $request['orderInfo'];
            $paymentDetailModel->amount = $payment['amount'];
            $paymentDetailModel->extra_data = $request['extraData'];
            $paymentDetailModel->save();
        }
    }

    public function updatePayment($data)
    {
        $paymentModel = Payment::where('request_id', $data->requestId)->first;
        if ($paymentModel) {
            $paymentModel->transaction_id = $data->transId;            
            $paymentModel->payment_type = $data->payType;
            $paymentModel->status  = $data->resultCode;
            $paymentSave = $paymentModel->save();
            if ($paymentSave) {
                $paymentDetailModel = PaymentDetail::where('payment_id', $paymentModel->id)->update([
                    'extra_data' => $data->extraData,
                    'message' => $data->message,
                    'order_info' => $data->orderInfo
                ]);                
            }
        }        
    }
}
