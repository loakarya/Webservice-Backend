<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'slug' => str_replace( " ", "-", $this->faker->words(3, true) ), 
            'thumbnail_url' => "https://resources.loakarya.co/products/LokaSmartTable_1.png",
            'picture_url_1' => "https://resources.loakarya.co/products/LokaSmartTable_2.png",
            'picture_url_2' => "https://resources.loakarya.co/products/LokaSmartTable_1.png",
            'title' => $this->faker->sentence(4),
            'price' => $this->faker->numberBetween(1000000, 4000000),
            'discount' => $this->faker->numberBetween(10, 100),
            'category' => $this->faker->numberBetween(0, 1),
            'detail' => $this->faker->paragraph(),
            'material' => $this->faker->sentence(3),
            'tokopedia_order_link' => 'https://www.tokopedia.com/',
            'shopee_order_link' => 'https://shopee.co.id/',
            'bukalapak_order_link' => 'https://www.bukalapak.com/'
        ];
    }
}
