<?php

namespace Dmn\Modules\Auth\Services;

use Carbon\Carbon;
use DateTime;
use Dmn\Modules\Auth\Contracts\AuthModel;
use Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException;
use Dmn\Modules\Auth\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Laravel\Sanctum\NewAccessToken;

class Auth
{
    /**
     * Assert authenticate
     *
     * @param array $credentials
     * @param string|null $type
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
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
     * 
     * @return void
     *
     * @throws \Dmn\Modules\Auth\Exceptions\InvalidCredentialsException
     * @throws \Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException
     */
    protected function assertUser(AuthModel $user, string $type = null): void
    {
        $this->assertType($user, $type);
        $this->assertCondition($user, $type);
    }

    /**
     * Assert condition
     *
     * @param \Dmn\Modules\Auth\Contracts\AuthModel $user
     * @param string|null $type
     * 
     * @return void
     * 
     * @throws \Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException
     */
    protected function assertCondition(AuthModel $user, string $type = null): void
    {
        $assertions = (config("dmod_auth.types.$type") ?? config('dmod_auth.default')) ?? [];
        foreach ($assertions as $col => $value) {
            if (is_array($value)) {
                $this->arrayAssertion($user, $col, $value);
                continue;
            }

            if ($user->$col !== $value) {
                $this->failedAssertion($user);
            }
        }
    }

    /**
     * Array assertion
     *
     * @param \Dmn\Modules\Auth\Contracts\AuthModel $user
     * @param string $col
     * @param array $value
     * @return void
     */
    protected function arrayAssertion(AuthModel $user, string $col, array $value): void
    {
        [$cond, $val] = $value;
        $userVal = $this->stringify($user->$col);
        $val = $this->stringify($val);
        $condition = "return $userVal $cond $val;";
        if (false === eval($condition)) {
            $this->failedAssertion($user);
        }
    }

    /**
     * Stringify
     *
     * @param mixed $value
     * @return void
     */
    protected function stringify($value): string
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            return "\"$value\"";
        }

        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d H:i:s');
            return "\"$value\"";
        }

        return $value;
    }

    /**
     * Failed assertion
     *
     * @param \Dmn\Modules\Auth\Contracts\AuthModel $user
     * @return void
     * @throws \Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException
     */
    protected function failedAssertion(AuthModel $user): void
    {
        $exception = new AuthenticationAssertionException();
        $exception->setUserData($user->toArray());
        throw $exception;
    }

    /**
     * Assert Type
     * 
     * @param \Dmn\Modules\Auth\Contracts\AuthModel $user
     * @param string $type
     *
     * @return void
     *
     * @throws \Dmn\Modules\Auth\Exceptions\InvalidCredentialsException
     */
    protected function assertType(AuthModel $user, string $type = null): void
    {
        if (is_null($type)) {
            return;
        }

        $userType = strtolower($user->{$user->getTypeIdentifier()});

        if ($userType !== $type) {
            throw new InvalidCredentialsException();
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
