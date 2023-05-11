<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\Examples\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
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
     * @test
     * @testdox It can login
     *
     * @return void
     */
    public function successfulLogin(): void
    {
        $user = User::create([
            'email' => 'email@email.com',
            'password' => Hash::make('123123123'),
            'name' => 'test',
            'is_active' => true
        ]);

        $response = $this->postJson(
            route('auth.login'),
            [
                'email' => $user->email,
                'password' => '123123123',
            ]
        );

        $response->assertCreated();
    }

    /**
     * @test
     * @testdox It can handle failed login
     *
     * @return void
     */
    public function failedLogin(): void
    {
        $response = $this->postJson(
            route('auth.login'),
            [
                'email' => 'test@email.com',
                'password' => '123123123',
                'is_active' => true
            ]
        );

        $response->assertUnauthorized();
        $this->assertEquals('invalid_credentials', $response->json('error'));
        $this->assertEquals('Invalid credentials.', $response->json('message'));
        $this->assertEquals('Invalid credentials.', $response->json('description'));
    }

    /**
     * @test
     * @testdox It can logout
     *
     * @return void
     */
    public function logout(): void
    {
        $user = User::create([
            'email' => 'email@email.com',
            'password' => Hash::make('123123123'),
            'name' => 'test',
            'is_active' => true
        ]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson(
            route('auth.logout')
        );

        $response->assertNoContent();
    }
}
