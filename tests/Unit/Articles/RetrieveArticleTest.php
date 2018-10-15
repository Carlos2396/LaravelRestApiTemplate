<?php

namespace Tests\Unit\Articles;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\User;

class RetrieveArticleTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of articles
     */
    public function testRetrieveArticlesList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $articles = Article::all();

        $response = $this->withHeaders(self::$headers)->get(route('articles.list'));

        $response
            ->assertStatus(200)
            ->assertExactJson($articles->toArray());
    }

    /**
     * Test succesful retrieve existent Article
     */
    public function testRetrieveExistentArticle()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $article = Article::first();

        $response = $this->withHeaders(self::$headers)->get(route('articles.show', $article->id));

        $response
            ->assertStatus(200)
            ->assertExactJson($article->toArray());
    }

    /**
     * Test fail retrieve Article with no existent Article
     */
    public function testRetrieveNonExistentArticle()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $article = Article::all()->last();

        $response = $this->withHeaders(self::$headers)->get(route('articles.show', $article->id + 1));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}
