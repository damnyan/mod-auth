<?php

namespace Dmn\Modules\Auth\Services;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Laravel\Sanctum\NewAccessToken;

class Auth
{
    /**
     * Authenticate
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public static function authenticate(User $user): NewAccessToken
    {
        $name = $user->email;

        return $user->createToken(
            $name,
            ['*'],
            (new Carbon())->addMinutes(config('sanctum.user_expiration'))
        );
    }
}
