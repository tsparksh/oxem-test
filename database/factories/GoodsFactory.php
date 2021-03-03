<?php

namespace Database\Factories;

use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Goods::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'price' => $this->faker->randomNumber($nbDigits = NULL, $strict = false),
            'quantity' => $this->faker->randomNumber($nbDigits = NULL, $strict = false),
            'external_id' => $this->faker->randomNumber($nbDigits = NULL, $strict = false),
        ];
    }
}
