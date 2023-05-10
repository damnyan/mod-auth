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
        if ($this->app['config']['dmn_mod_auth.routes.enabled'] ?? true) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        }
    }
}
