<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\Examples\Models\User;
use Tests\TestCase;

class AuthTypeTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app)
    {
        Config::set('dmn_mod_auth.types', [
            'client' => [
                'is_active' => true,
            ],
            'admin' => [
                'is_active' => true,
            ],
        ]);
        return parent::getPackageProviders($app);
    }

    /**
     * @test
     * @testdox It can handle mismatched type
     *
     * @return void
     */
    public function mismatchedType(): void
    {
        $user = User::create([
            'email' => 'email@email.com',
            'type' => 'admin',
            'is_active' => false,
            'password' => Hash::make('123123123'),
        ]);

        $response = $this->postJson(
            route('auth.login.client'),
            [
                'email' => 'email@email.com',
                'password' => '123123123',
            ]
        );

        $response->assertUnauthorized();
    }

    /**
     * @test
     * @testdox It can handle mismatched wheres
     *
     * @return void
     */
    public function mismatchedWheres(): void
    {
        $user = User::create([
            'email' => 'email@email.com',
            'type' => 'admin',
            'is_active' => false,
            'password' => Hash::make('123123123'),
        ]);

        $response = $this->postJson(
            route('auth.login.admin'),
            [
                'email' => 'email@email.com',
                'password' => '123123123',
            ]
        );

        $response->assertUnauthorized();
    }
}
