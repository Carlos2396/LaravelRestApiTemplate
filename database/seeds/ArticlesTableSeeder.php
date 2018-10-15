<?php

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->truncate();

        $articles = [
            [
                'title' => "Article 1",
                'body' => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris dictum facilisis augue. Fusce aliquam vestibulum ipsum. Maecenas sollicitudin."
            ],
            [
                'title' => "Article 2",
                'body' => "Praesent vitae arcu tempor neque lacinia pretium. Maecenas lorem. Etiam dui sem, fermentum vitae, sagittis id, malesuada in, quam."
            ]
        ];

        foreach($articles as $article) {
            Article::create($article);
        }
    }
}
