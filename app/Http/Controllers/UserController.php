<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function registration(Request $request): JsonResponse
    {

        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);

        try {

            DB::beginTransaction();
                $inputs = $request->all();
                $inputs['password'] = bcrypt($inputs['password']);

                $user = User::create($inputs);

                $response = [];
                $response['success'] = true;
                $response['token'] = $user->createToken('api:application')->accessToken;
                $response['name'] = $user;

            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(Request $request): JsonResponse
    {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();

            $resArr = [];
            $resArr['token'] = $user->createToken('api:application')->accessToken;
            $resArr['name'] = $user->name;

            return response()->json($resArr, 200);
        }else{
            return response()->json(["errors" => "Unauthorized Access"], 203);
        }
    }

    public function userDetail(Request $request){

        return response()->json(Auth::user(), 200);
    }
}
