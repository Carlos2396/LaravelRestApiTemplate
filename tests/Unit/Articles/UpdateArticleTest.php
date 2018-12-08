<?php

namespace Tests\Unit\Articles;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Article;

class UpdateArticleTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test update fail with null fields successful
     *
     * @return void
     */
    public function testUpdateNullFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = Article::first();
        $article->title = null;
        $article->body = null;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('articles.update', $article->id),
                $article->toArray()
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
     * Test update fail with a too long title
     *
     * @return void
     */
    public function testCreateTooLongTitle()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $article = Article::first();
        $article->title = 'More than 20 characters title';
        $article->body = 'Ok body';
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('articles.update', $article->id),
                $article->toArray()
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'title' => ['The title may not be greater than 20 characters.']
                ]
            ]);
    }

    /**
     * Test update fail with a too long body
     *
     * @return void
     */
    public function testCreateTooLongBody()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $article = Article::first();
        $article->title = 'Ok title';
        $article->body = 'Pellentesque venenatis massa rhoncus, cursus ipsum at, rhoncus ligula. Nunc laoreet, enim quis pretium fermentum, nisi nisi tempus nunc, vel blandit purus lorem maximus mi. Phasellus ornare lorem scelerisque lacus suscipit blandit. Nulla fringilla felis ultricies ipsum congue volutpat.';
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('articles.update', $article->id),
                $article->toArray()
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
     * Test create successful
     *
     * @return void
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = Article::first();
        $article->title = 'Ok title';
        $article->body = 'Ok body';
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('articles.update', $article->id),
                $article->toArray()
            );

        $response
            ->assertStatus(200)
            ->assertJson(collect($article)->except('updated_at')->toArray());
    }
}
