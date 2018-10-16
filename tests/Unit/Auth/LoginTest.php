<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successful login with correct creedentials
     */
    public function testSuccesfulLogin()
    {
        parent::withoutMiddleware(App\Http\Middleware\RoleMiddleware::class);

        $user = User::first();
        $creedentials = [
            'email' => 'admin@test.com',
            'password' => 'secret'
        ];

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('login'),
                $creedentials
            );
        
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'token'
            ])
            ->assertJson([
                'user' => $user->toArray()
            ]);
    }

    /**
     * Test failed Login with bad credentials
     */
    public function testUnsuccesfulLogin()
    {
        parent::withoutMiddleware(App\Http\Middleware\RoleMiddleware::class);

        $user = User::first();
        $creedentials = [
            'email' => 'admin@test.com',
            'password' => 'secreto'
        ];

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('login'),
                $creedentials
            );
        
        
        $response
            ->assertStatus(401)
            ->assertExactJson([
                'message' => 'Bad credentials'
            ]);
    }
}
