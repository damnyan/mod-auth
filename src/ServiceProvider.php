<?php

namespace Dmn\Modules\Auth;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('dmod_auth.php')
        ], 'dmod-auth-config');

        $this->publishes([
            __DIR__.'/../swag_docs/DModAuth.php' => base_path('swag_docs/DModAuth.php')
        ], 'dmod-auth-swag');

        if ($this->app['config']['dmod_auth.routes.enabled'] ?? false) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        }
    }
}
