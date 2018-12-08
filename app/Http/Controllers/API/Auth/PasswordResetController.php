<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Helpers\ResponseHelper;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Validator;
use App\User;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        // validates request data, returns errors if fails
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [
                'email' => $user->email,
                'token' => str_random(60)
        ]);

        $user->notify(new PasswordResetRequest($passwordReset->token));

        return response()->json(null, 204);
    }

    /**
     * Reset password if token is valid.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:password_resets,email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string|exists:password_resets,token'
        ]);

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $passwordReset = PasswordReset::where($request->only('email', 'token'))->first();

        if($passwordReset->updated_at->addMinutes(720)->isPast()) {
            return response()->json(['message', 'The provided token has expired.'], 401);
        }

        $user = User::where('email', $request->email)->get()->first();
        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess());

        return response()->json(null, 204);
    }
}
