<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationRequest;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Hash;
use Validator;

use App\User;

class RegisterController extends Controller
{
    static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255|email|unique:users',
        'password' => 'required|string|min:6|max:30|confirmed'
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), self::$rules);

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::create($request->all());
        $user->password = Hash::make($request->password);
        $user->save();

        $user->assignRole('user');

        try {
            $user->notify(new EmailVerificationRequest($user->confirmation_code));
        }
        catch(Exception $e) {
            return response()->json(null, 503);
        }

        return response()->json($user, 201);
    }
}
