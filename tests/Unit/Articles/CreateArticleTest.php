<?php

namespace Tests\Unit\Articles;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateArticleTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test fail create Article with null fields
     */
    public function testCreateNullFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = [
            'title' => null,
            'body2' => 'Test body'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('articles.store'),
                 $article
            );

        $response
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'title' => ['The title field is required.'],
                    'body' => ['The body field is required.']
                ]
            ]);
    }

    /**
     * Test fail create Article with a too long title
     */
    public function testCreateTooLongTitle()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = [
            'title' => 'More than 20 characters title',
            'body' => 'Ok body'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('articles.store'),
                 $article
            );

        $response
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'title' => ['The title may not be greater than 20 characters.']
                ]
            ]);
    }

    /**
     * Test fail create Article with a too long body
     */
    public function testCreateTooLongBody()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = [
            'title' => 'Ok title',
            'body' => 'Pellentesque venenatis massa rhoncus, cursus ipsum at, rhoncus ligula. Nunc laoreet, enim quis pretium fermentum, nisi nisi tempus nunc, vel blandit purus lorem maximus mi. Phasellus ornare lorem scelerisque lacus suscipit blandit. Nulla fringilla felis ultricies ipsum congue volutpat.'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('articles.store'),
                 $article
            );

        $response
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'body' => ['The body may not be greater than 100 characters.']
                ]
            ]);
    }

    /**
     * Test successful create Article
     *
     * @return void
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = [
            'title' => 'Ok title',
            'body' => 'Ok body'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('articles.store'),
                 $article
            );

        $response
            ->assertStatus(201)
            ->assertJson($article);
    }
}
