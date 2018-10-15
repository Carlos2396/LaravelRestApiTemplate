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

            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], 200); 
        } 
        else{ 
            return response()->json(['error'=>'Bad credentials'], 401); 
        } 
    }

    public function logout(Request $request) {
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();

        return response($token, 200);
    }
}
