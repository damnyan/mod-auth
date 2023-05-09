<?php

namespace Dmn\Modules\Auth\Controllers;

use Dmn\Modules\Auth\Exceptions\InvalidCredentialsException;
use Dmn\Modules\Auth\Requests\LoginRequest;
use Dmn\Modules\Auth\Resources\Auth as ResourcesAuth;
use Dmn\Modules\Auth\Services\Auth as ServicesAuth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /**
     * Login
     *
     * @param \Dmn\Modules\Auth\Requests\LoginRequest $request
     * @return \Dmn\Modules\Auth\Resources\Auth
     */
    public function login(LoginRequest $request, string $type = null): ResourcesAuth
    {
        $auth = ServicesAuth::assertAuthenticate($request->validated(), $type);
        return new ResourcesAuth($auth);
    }

    /**
     * Auth
     *
     * @param \Dmn\Modules\Auth\Requests\LoginRequest $request
     * @param string $type
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    // protected function auth(LoginRequest $request, string $type = null): NewAccessToken
    // {
    //     if (false === Auth::attempt($request->validated())) {
    //         throw new InvalidCredentialsException();
    //     }

    //     $user = Auth::user();
    //     $config = config("dmn_mod_auth.types.$type");

    //     return 
    // }
}
