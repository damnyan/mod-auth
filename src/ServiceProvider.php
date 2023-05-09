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
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
    }
}
