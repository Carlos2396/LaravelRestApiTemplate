<?php

namespace Tests\Unit\Articles;

use Tests\TestCase;
use Tests\Helper;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteArticleTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test fail delete as Article does not exist
     */
    public function testDeleteNonExistentArticle()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = Article::all()->last();

        $response = $this->withHeaders(self::$headers)->delete(route('articles.delete', $article->id + 1));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }

    /**
     * Test successful delete Article
     */
    public function testDeleteExistentArticle()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $article = Article::first();

        $response = $this->withHeaders(self::$headers)->delete(route('articles.delete', $article->id));
        
        $response->assertStatus(204);
    }
}
