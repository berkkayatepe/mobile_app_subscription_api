<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required|numeric',
            'appId' => 'required',
            'language' => 'required',
            'os' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 401);
        } else {
            $request->uid = intval($request->uid);
            $user = User::where('id', $request->uid)->first();
            if ($user) {
                $model = Device::firstOrNew(['user_id' => $user->id, 'app_id' => $request->appId]);
                $model->language = $request->language;
                $model->os = $request->os;
                $model->save();
                $client_token = $model->createToken('client-token')->plainTextToken;
                $model->where('user_id', $user->id)->where('app_id', $request->appId)->update(['client_token' => $client_token]);
                return response()->json(['success' => true, 'client-token' => $client_token], 200);
            } else {
                return response()->json(['success' => false, 'errors' => "Not Found"], 404);
            }
        }
    }
}
