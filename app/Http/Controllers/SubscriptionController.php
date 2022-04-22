<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt' => 'required',
            'client_token' => 'required',
            'expire_date' => 'required|date',
        ]);
        $response = array();
        $statusCode = 200;
        if ($validator->fails()) {
            $response = ['success' => false, 'errors' => $validator->errors()];
            $statusCode = 401;
        } else {
            $device = Device::select("id", "app_id", "os")->where('client_token', $request->client_token)->first();
            $last_character = substr($request->receipt, -1);
            if ($last_character % 2 == 0) {
                $response = ['success' => false, 'errors' => 'Verification Failed'];
                $statusCode = 401;
            } else {
                $expire_date = date('Y-m-d H:i:s', strtotime($request->expire_date));
                $subscription = new Subscription();
                $subscription->device_id = $device->id;
                $subscription->app_id = $device->app_id;
                $subscription->client_token = $request->client_token;
                $subscription->receipt = $request->receipt;
                $subscription->subscription_date = date('Y-m-d H:i:s');
                $subscription->expiry_date = $expire_date;
                $subscription->subscription_status = 'started';
                if ($subscription->save()) {
                    $response = ['success' => true, 'message' => 'Verified and Subscription has purchased!'];
                    $statusCode = 200;
                }
            }
        }
        return Response()->json($response, $statusCode);
    }

    public function check_subscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_token' => 'required',
        ]);
        $response = array();
        $statusCode = 200;
        if ($validator->fails()) {
            $response = ['success' => false, 'errors' => $validator->errors()];
            $statusCode = 401;
        } else {
            $device = Device::select("id", "app_id")->where('client_token', $request->client_token)->first();
            if (!empty($device)) {
                $subscription = Subscription::where('device_id', $device->id)->where('app_id', $device->app_id)->first();
                if (!empty($subscription)) {
                    $response = ['success' => true, 'response' => $subscription->subscription_status];
                    $statusCode = 200;
                }
            }
        }
        return Response()->json($response, $statusCode);
    }
}
