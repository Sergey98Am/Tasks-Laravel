<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use JWTAuth;
use Carbon\Carbon;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        try {
            $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

            if (!$url) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                "url" => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->stateless()->user();

            if (!$providerUser->token) {
                throw new \Exception('Failed to login');
            }

            $appUser = User::where('email', $providerUser->getEmail())->first();

            if (!$appUser) {
                $appUser = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                $socialAccount = SocialAccount::create([
                    'provider' => $provider,
                    'provider_user_id' => $providerUser->getId(),
                    'user_id' => $appUser->id
                ]);

                if (!$appUser || !$socialAccount) {
                    throw new \Exception('Something went wrong');
                }
            } else {
                $socialAccount = $appUser->socialAccounts()->where('provider', $provider)->first();

                if (!$socialAccount) {
                    if ($appUser->email_verified_at == NUll) {
                        $appUser->update([
                            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                    SocialAccount::create([
                        'provider' => $provider,
                        'provider_user_id' => $providerUser->getId(),
                        'user_id' => $appUser->id
                    ]);
                }
            }

            return response()->json([
                'token' => JWTAuth::fromUser($appUser),
                'user' => User::find($appUser->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
