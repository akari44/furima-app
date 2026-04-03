<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;


class UserFactory extends Factory
{
   
    protected static ?string $password;

    public function definition(): array
    {
        $avatarPaths = collect(range(1, 5))
            ->map(fn($n) => 'avatars/avatar_' . str_pad($n, 2, '0', STR_PAD_LEFT) . '.jpg')
            ->all();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password1234'),
            'postal_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building_name' => $this->faker->optional()->secondaryAddress(),
            'avatar_path' => Arr::random($avatarPaths),
        ];
    }
    

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
