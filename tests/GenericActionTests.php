<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenericActionTests extends TestCase
{

    protected $env;

    public function __construct($env) {
        $this->env = $env;
    }

    public function testSuccessfulCreate(String $routeName, String $method, Array $headers, Array $data, Array $expected) {
        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus(201)
            ->assertJson($expected);
    }
}