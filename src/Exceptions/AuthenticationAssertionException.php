<?php

namespace Dmn\Modules\Auth\Exceptions;

use Dmn\Exceptions\Exception;
use Illuminate\Http\Response;

class AuthenticationAssertionException extends Exception
{
    protected $code = 'invalid_credentials';

    protected $message = 'Invalid credentials.';

    protected $httpStatusCode = Response::HTTP_UNAUTHORIZED;

    public readonly array $userData;

    public function setUserData(array $userData): void
    {
        $this->userData = $userData;
    }
}
