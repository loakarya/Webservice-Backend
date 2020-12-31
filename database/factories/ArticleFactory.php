<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'thumbnail_url' => $this->faker->imageUrl(),
            'slug' => str_replace( " ", "-", $this->faker->words(3, true) ), 
            'title' => $this->faker->sentence(4),
            'subtitle' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph(4)
        ];
    }
}
