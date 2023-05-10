<?php

namespace Tests;

use Dmn\Modules\Auth\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        Config::set('auth.providers.users.model', \Tests\Examples\Models\User::class);
        Config::set('dmod_auth.default', [
            'is_active' => true
        ]);
        Config::set('dmod_auth.types', []);
        Config::set('dmod_auth.routes.prefix', 'api');

        $guards = config('auth.guards');
        $guards['sanctum'] = [
            'driver' => 'session',
            'provider' => 'users',
        ];

        Config::set('auth.guards', $guards);
    }

    /**
     * Run the database migrations for the application.
     *
     * @return void
     */
    public function runDatabaseMigrations(): void
    {
        $migrationPath = __DIR__ . '/database/migrations';

        $this->artisan(
            'migrate:fresh --realpath --path="'
            . $migrationPath
            . '"'
        );

        $this->beforeApplicationDestroyed(function () use ($migrationPath) {
            $this->artisan(
                'migrate:rollback --realpath --path="'
                . $migrationPath
                . '"'
            );
        });
    }
}
