<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    //Eroor code list
    //https://gist.github.com/jeffochoa/a162fc4381d69a2d862dafa61cda0798

    public function registration(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_OK);
        }

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
            return response()->json($response, Response::HTTP_OK);
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

            return response()->json($resArr, Response::HTTP_OK);
        }else{
            return response()->json(["errors" => "Unauthorized Access"], Response::HTTP_BAD_REQUEST);
        }
    }

    public function userDetail(Request $request){

        return response()->json(Auth::user(), Response::HTTP_OK);
    }
}
