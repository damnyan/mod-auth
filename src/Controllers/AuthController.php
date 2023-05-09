<?php

namespace Dmn\Modules\Auth\Controllers;

use Dmn\Modules\Auth\Requests\LoginRequest;
use Dmn\Modules\Auth\Resources\Auth as ResourcesAuth;
use Dmn\Modules\Auth\Services\Auth as ServicesAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

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
     * Logout
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
