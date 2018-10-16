<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\User;

class RolesTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successfully accessing admin protected route with admin user
     */
    public function testAccessingAdminProtectedRouteWithAdminUser()
    {
        parent::withoutMiddleware(\App\Http\Middleware\Authenticate::class);

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

        $response = $this->withHeaders($headers)->get(route('admin.check'));
        
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'success' => true
            ]);
    }

    /**
     * Test successfully accessing admin protected route with admin user
     */
    public function testAccessingAdminProtectedRouteWithNotAdminUser()
    {
        parent::withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $user = null;
        $headers = self::$headers;    
        $credentials = [
            'email' => 'user@test.com',
            'password' => 'secret'
        ];

        if(Auth::attempt($credentials)) { 
            $user = Auth::user();
            $token =  $user->createToken('MyApp')->accessToken;
            $headers['Authorization'] = 'Bearer '.$token;
        }

        $response = $this->withHeaders($headers)->get(route('admin.check'));
        
        $response
            ->assertStatus(401)
            ->assertExactJson([
                'message' => 'Do not have proper permissions'
            ]);
    }
}
