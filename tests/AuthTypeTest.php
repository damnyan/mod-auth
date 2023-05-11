<?php

namespace Tests;

use Carbon\Carbon;
use Dmn\Modules\Auth\Exceptions\AuthenticationAssertionException;
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
        return parent::getPackageProviders($app);
    }

    /**
     * {@inheritDoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        Config::set('dmod_auth.types', [
            'client' => [
                'email_verified_at' => '!null',
                'is_active' => true,
            ],
            'admin' => [
                'email_verified_at' => ['!==', null],
                'is_active' => ['===', true],
                'type' => 'admin',
                'col_string' => ['===', 'this is string'],
                'col_int' => ['===', 123],
            ],
            'string' => [
                'type' => 'admin',
            ],
        ]);
    }

    /**
     * @test
     * @testdox It can handle mismatched type
     *
     * @return void
     */
    public function mismatchedType(): void
    {
        User::create([
            'email' => 'email@email.com',
            'type' => 'admin',
            'is_active' => true,
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
     * @testdox It can succesfully login with matching wheres
     *
     * @return void
     */
    public function matchedWheres(): void
    {
        User::create([
            'email' => 'email@email.com',
            'type' => 'admin',
            'is_active' => true,
            'password' => Hash::make('123123123'),
            'email_verified_at' => new Carbon(),
        ]);

        $response = $this->postJson(
            route('auth.login.admin'),
            [
                'email' => 'email@email.com',
                'password' => '123123123',
            ]
        );

        $response->assertCreated();
    }

    /**
     * @test
     * @testdox It can handle mismatched wheres with Array
     *
     * @return void
     */
    public function mismatchedWheresWithArray(): void
    {
        $this->withoutExceptionHandling();
        User::create([
            'email' => 'email@email.com',
            'type' => 'admin',
            'is_active' => false,
            'password' => Hash::make('123123123'),
        ]);

        try {
            $this->postJson(
                route('auth.login.admin'),
                [
                    'email' => 'email@email.com',
                    'password' => '123123123',
                ]
            );
        } catch (AuthenticationAssertionException $e) {
            $this->assertIsArray($e->userData);
        }
    }

    /**
     * @test
     * @testdox It can handle mismatched wheres for string
     *
     * @return void
     */
    public function mismatchedWheresForString(): void
    {
        $this->withoutExceptionHandling();
        User::create([
            'email' => 'email@email.com',
            'type' => 'string',
            'is_active' => false,
            'password' => Hash::make('123123123'),
        ]);

        try {
            $this->postJson(
                route('auth.login.string'),
                [
                    'email' => 'email@email.com',
                    'password' => '123123123',
                ]
            );
        } catch (AuthenticationAssertionException $e) {
            $this->assertIsArray($e->userData);
        }
    }
}
