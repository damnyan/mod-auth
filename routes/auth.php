<?php

use Dmn\Modules\Auth\Controllers\AuthController;
use Dmn\Modules\Auth\Requests\LoginRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

$config = Config::get('dmod_auth');
$types = $config['types'];
$prefix = $config['routes']['prefix'];

Route::delete("$prefix/auth/logout", [AuthController::class, 'logout'])
    ->middleware(['auth:sanctum'])
    ->name('auth.logout');

if (!empty($types)) {
    foreach ($types as $type => $wheres) {
        Route::post("$prefix/auth/login/$type", function (LoginRequest $request) use ($type) {
            $controller = app(AuthController::class);
            return $controller->login($request, $type);
        })->name("auth.login.$type");
    }
    return;
}
Route::post("$prefix/auth/login", [AuthController::class, 'login'])->name('auth.login');
