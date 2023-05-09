<?php

namespace Dmn\Modules\Auth\Services;

use Carbon\Carbon;
use Dmn\Modules\Auth\Contracts\AuthModel;
use Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException;
use Dmn\Modules\Auth\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Laravel\Sanctum\NewAccessToken;

class Auth
{
    public static function assertAuthenticate(
        array $credentials,
        string $type = null
    ): NewAccessToken {

        if (!FacadesAuth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        $user = FacadesAuth::user();

        (new static)->assertUser($user, $type);

        return self::authenticate($user);
    }

    /**
     * Assert user
     *
     * @param AuthModel $user
     * @param string|null $type
     * @throws \Dmn\Modules\Auth\Exceptions\InvalidCredentialsException
     * @throws \Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException
     * @return void
     */
    protected function assertUser(AuthModel $user, string $type = null): void
    {
        if (is_null($type)) {
            return;
        }

        $config = config("dmn_mod_auth.types.$type");

        $userType = strtolower($user->{$user->getTypeIdentifier()});

        if ($userType !== $type) {
            throw new InvalidCredentialsException();
        }

        $userArr = Arr::only($user->toArray(), array_keys($config));

        $diff = array_diff($userArr, $config);

        if (count($diff) > 0) {
            $exception = new AuthenticationAssertionException();
            $exception->setUserData($user->toArray());
            throw $exception;
        }
    }

    /**
     * Authenticate
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public static function authenticate(AuthModel $user): NewAccessToken
    {
        $name = $user->email;

        return $user->createToken(
            $name,
            ['*'],
            (new Carbon())->addMinutes(config('sanctum.user_expiration'))
        );
    }
}
