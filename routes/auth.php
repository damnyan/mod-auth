<?php

use Dmn\Modules\Auth\Controllers\AuthController;
use Dmn\Modules\Auth\Requests\LoginRequest;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    $types = config('dmn_mod_auth.types');
    if (!empty($types)) {
        foreach ($types as $type => $wheres) {
            Route::post("login/$type", function (LoginRequest $request) use ($type) {
                $controller = app(AuthController::class);
                return $controller->login($request, $type);
            })->name("auth.login.$type");
        }
        return;
    }
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});
