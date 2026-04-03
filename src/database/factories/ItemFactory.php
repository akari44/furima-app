<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use App\Models\User;


class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'item_name' => $this->faker->realText(12),
            'seller_id' => User::factory(),
            'price' => $this->faker->numberBetween(300, 20000),
            'brand' => $this->faker->optional()->company(),
            'description' => $this->faker->realText(80),
            'condition' => Arr::random([
                'good',
                'no_visible_damage',
                'some_damage',
                'bad',
            ]),
            'status' => 'selling',
        ];
    }
}
