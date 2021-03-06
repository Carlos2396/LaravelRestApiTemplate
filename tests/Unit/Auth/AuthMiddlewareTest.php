<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthMiddlewareTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successfully accessing a protected route with a logged user token
     */
    public function testAccessingProtectedRouteWithValidToken()
    {
        parent::withoutMiddleware(App\Http\Middleware\RoleMiddleware::class);

        $user = null;
        $headers = self::$headers;    
        $credentials = [
            'email' => 'admin@test.com',
            'password' => 'secret'
        ];

        if(Auth::attempt($credentials)) { 
            $user = Auth::user();
            $token =  $user->createToken('MyApp')->accessToken;
            $headers['Authorization'] = 'Bearer '.$token;
        }

        $response = $this->withHeaders($headers)->get(route('auth.check'));
        
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'authenticated' => true
            ]);
    }

    /**
     * Test accessing a protected route with no authorization header specified
     */
    public function testAccessingProtectedRouteWithNoToken()
    {
        parent::withoutMiddleware(App\Http\Middleware\RoleMiddleware::class);

        $response = $this->withHeaders(self::$headers)->get(route('auth.check'));
        
        $response
            ->assertStatus(401)
            ->assertExactJson([
                'message' => 'The user needs to be authenticated'
            ]);
    }

    /**
     * Test accessing a protected route with a revoked authorization token
     */
    public function testAccessingProtectedRouteWithRevokedToken()
    {
        parent::withoutMiddleware(App\Http\Middleware\RoleMiddleware::class);

        $user = null;
        $headers = self::$headers;    
        $credentials = [
            'email' => 'admin@test.com',
            'password' => 'secret'
        ];

        if(Auth::attempt($credentials)) { 
            $user = Auth::user();
            $token =  $user->createToken('MyApp');
            $token->token->revoke();

            $headers['Authorization'] = 'Bearer '.$token->accessToken;
        }

        $response = $this->withHeaders($headers)->get(route('auth.check'));
        
        $response
            ->assertStatus(401)
            ->assertExactJson([
                'message' => 'The user needs to be authenticated'
            ]);
    }
}
