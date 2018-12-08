<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Notifications\EmailVerificationRequest;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use Validator;
use App\User;

class AccountVerificationController extends Controller
{
    /**
     * Verifies account of user with provided uuid.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount($uuid) {
        // validates request data, returns errors if fails
        $validator = Validator::make(['confirmation_code' => $uuid], [
            'confirmation_code' => 'required|string|exists:users,confirmation_code'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::where('confirmation_code', $uuid)->get()->first();

        if($user->email_verified_at != null) {
            return response()->json(['message' => 'Account already confirmed.'], 400);
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json(null, 204);
    }

    /**
     * Resend the verification email to acoount with the provided email.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationEmail(string $email) {
        // validates request data, returns errors if fails
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|string|email|exists:users,email'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::where('email', $email)->get()->first();
        
        if($user->email_verified_at != null) {
            return response()->json(['message' => 'Account already confirmed.'], 400);
        }

        $user->notify(new EmailVerificationRequest($user->confirmation_code));

        return response()->json(null, 204);
    }
}
