<?php

namespace Dmn\Modules\Auth\Exceptions;

use Dmn\Exceptions\Exception;
use Illuminate\Http\Response;

class InvalidCredentialsException extends Exception
{
    protected $code = 'invalid_credentials';

    protected $message = 'Invalid credentials.';

    protected $httpStatusCode = Response::HTTP_UNAUTHORIZED;
}
