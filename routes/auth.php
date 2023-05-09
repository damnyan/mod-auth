<?php

use Dmn\Modules\Auth\Controllers\AuthController;
use Dmn\Modules\Auth\Requests\LoginRequest;
use Illuminate\Support\Facades\Route;

$types = config('dmn_mod_auth.types');
if (!empty($types)) {
    foreach ($types as $type => $wheres) {
        Route::post("auth/login/$type", function (LoginRequest $request) use ($type) {
            $controller = app(AuthController::class);
            return $controller->login($request, $type);
        })->name("auth.login.$type");
    }
    return;
}
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::delete('auth/logout', [AuthController::class, 'logout'])
    ->middleware(['auth:sanctum'])
    ->name('auth.logout');
