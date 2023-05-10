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
        if ($this->app['config']['dmod_auth.routes.enabled'] ?? true) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        }

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('dmod_auth.php')
        ], 'dmod-config');
    }
}
