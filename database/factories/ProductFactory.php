<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unit = ['kg','gr','ton'];
        return [
            'title'=>$this->faker->title,
            'body'=>$this->faker->text,
            'volume'=>$this->faker->randomDigit(),
            'unit'=>$unit[rand(0,2)],
            'file'=>$this->faker->imageUrl,
            'category_id'=>$this->faker->numberBetween(1,2)
        ];
    }
}
