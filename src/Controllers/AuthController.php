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
    public function login(LoginRequest $request): ResourcesAuth
    {
        $token = $this->auth($request);
        return new ResourcesAuth($token);
    }

    /**
     * Auth
     *
     * @param \Dmn\Modules\Auth\Requests\LoginRequest $request
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    protected function auth(LoginRequest $request): NewAccessToken
    {
        if (false === Auth::attempt($request->validated())) {
            throw new InvalidCredentialsException();
        }

        $user = Auth::user();


        return ServicesAuth::authenticate($user);
    }
}
