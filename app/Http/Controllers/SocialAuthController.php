<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SocialAccountService;
use Socialite;


class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->with(['hd' => 'gems.techverx.com','techverx.com'])->redirect();
    }   

    public function callback(SocialAccountService $service, $provider)
    {
        $user = $service->createOrGetUser(Socialite::driver($provider)->user(), $provider);

        auth()->login($user);

        return redirect()->to('/home');
    }
}