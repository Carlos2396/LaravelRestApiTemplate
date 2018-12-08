<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationRequest;
use App\Helpers\ResponseHelper;
use Validator;

class RegisterController extends Controller
{
    static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|max:30|confirmed'
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(self::$rules, $request->all());

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
            return response()->json(null, 504);
        }

        return response()->json($user, 201);
    }
}
