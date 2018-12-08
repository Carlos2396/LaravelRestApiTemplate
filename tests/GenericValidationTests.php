<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenericValidationTests extends TestCase
{

    protected $env, $vec;

    public function __construct($env) {
        $this->env = $env;
        $this->vec = env('VALIDATION_ERROR_CODE', 422);
    }

    public function testEmptyAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute, String $repeated = null) {
        $data[$attribute] = '';
        if($repeated != null) {
            $data[$repeated] = $data[$attribute];
        }
        
        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.required', ['attribute' => $attribute])]
                ]
            ]);
    }

    public function testNullAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute, String $repeated = null) {
        $data[$attribute] = null;
        if($repeated != null) {
            $data[$repeated] = $data[$attribute];
        }

        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.required', ['attribute' => $attribute])]
                ]
            ]);
    }

    public function testMinStringAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute, int $min, String $repeated = null) {
        $data[$attribute] = str_random($min-1);
        if($repeated != null) {
            $data[$repeated] = $data[$attribute];
        }

        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.min.string', ['attribute' => $attribute, 'min' => $min])]
                ]
            ]);
    }

    public function testMaxStringAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute, int $max, String $repeated = null) {
        $data[$attribute] = str_random($max+1);
        if($repeated != null) {
            $data[$repeated] = $data[$attribute];
        }

        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.max.string', ['attribute' => $attribute, 'max' => $max])]
                ]
            ]);
    }

    public function testInvalidEmailAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute, String $repeated = null) {
        $data[$attribute] = str_random(15);
        if($repeated != null) {
            $data[$repeated] = $data[$attribute];
        }
        
        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.email', ['attribute' => $attribute])]
                ]
            ]);
    }

    public function testNotUniqueAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute) {
        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.unique', ['attribute' => $attribute])]
                ]
            ]);
    }

    public function testNotConfirmedAttribute(String $routeName, String $method, Array $headers, Array $data, String $attribute) {
        $data[$attribute.'_confirmation'] = null;

        $response = $this->env
            ->withHeaders($headers)
            ->json($method, route($routeName), $data);
        
        $response
            ->assertStatus($this->vec)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    $attribute => [__('validation.confirmed', ['attribute' => $attribute])]
                ]
            ]);
    }
}
