<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiAuthController extends BaseController
{
    //
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('FCPortal')->accessToken;
                $response = ['token' => $token->token,"role" => $user->role, "user_id" => $user->id, "fullName" => ucwords($user->first_name)." ".ucwords($user->last_name)];
                return $this->sendResponse($response);
            } else {
                $response = ["message" => "Password mismatch"];
                return $this->sendResponse($response,0,422);
            }
        } else {
            $response = ["message" => "User does not exist"];
            return $this->sendResponse($response,0,422);
        }
    }
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return $this->sendResponse($response);
    }
}
