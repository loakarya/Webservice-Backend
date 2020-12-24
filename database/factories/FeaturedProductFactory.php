<?php

namespace Database\Factories;

use App\Models\FeaturedProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeaturedProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeaturedProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => $this->faker->numberBetween(1, 10),
            'user_id' => 0
        ];
    }
}
