<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name'=> $this->faker->lastName,
            'email'=> $this->faker->unique()->safeEmail,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email_verified_at' => now(),
            'address'=> $this->faker->address,
            'zip_code'=> $this->faker->postcode,
            'city'=> $this->faker->city,
            'province'=> $this->faker->state,
            'country'=> $this->faker->country,
            'remember_token' => Str::random(10),
        ];
    }
}
