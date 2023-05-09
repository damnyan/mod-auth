<?php

use Dmn\Modules\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});
