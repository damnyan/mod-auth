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
        // $enableRoutes = $this->app['config']['dmn_mod_auth.enable_routes'] ?? true;
        // dd($enableRoutes);
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
    }
}
