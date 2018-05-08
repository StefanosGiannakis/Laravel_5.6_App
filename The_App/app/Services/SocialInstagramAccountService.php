<?php

namespace App\Services;
use App\SocialInstagramAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

// It will try to find provider’s account in the system and 
// if it is not present it will create a new user. This method 
// will also try to associate the social account with the email
//  address in case that user already has an account.

class SocialInstagramAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialInstagramAccount::whereProvider('Instagram')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialInstagramAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'password' => md5(rand(1,10000)),
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}