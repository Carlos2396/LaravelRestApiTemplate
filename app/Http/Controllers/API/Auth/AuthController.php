<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request) {

        if(Auth::attempt($request->only('email', 'password'))) { 
            $user = Auth::user();
            $token =  $user->createToken('MyApp')->accessToken;
             
            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200); 
        } 
        else{ 
            return response()->json(['message'=>'Bad credentials'], 401); 
        } 
    }

    public function logout() {
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();

        return response(['success' => true], 200);
    }

    public function check(Request $request) {
        return response()->json([
            'authenticated' =>  Auth::check()
        ], 200);
    }
}
