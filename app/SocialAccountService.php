<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Spatie\Permission\Models\Role;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name'  => $providerUser->getName()
                ]);

                $user->assignRole('engineer');
            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}