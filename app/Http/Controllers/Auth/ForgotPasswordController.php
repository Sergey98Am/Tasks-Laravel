<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user && !$user->email_verified_at) {
                throw new \Exception('Email is not verified');
            }

            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            if ($response !== Password::RESET_LINK_SENT) {
                throw new \Exception('Failed to send mail');
            }

            return response()->json([
                'message' => 'Email sent',
                'response' => $response,
                'user' => Password::RESET_LINK_SENT,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function broker()
    {
        return Password::broker();
    }
}
