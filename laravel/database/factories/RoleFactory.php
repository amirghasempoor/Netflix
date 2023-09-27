<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [

            [
                'id' => 1,
                'name' => 'admin',
                'guard_name' => 'operator',
            ],
            [
                'id' => 2,
                'name' => 'user_managing',
                'guard_name' => 'operator',
            ],
            [
                'id' => 3,
                'name' => 'movie_managing',
                'guard_name' => 'operator',
            ],
        ];

            $randomIndex = rand(0, count($roles) - 1);

            $name = $roles[$randomIndex]['name'];

            $guard_name = $roles[$randomIndex]['guard_name'];

            return [
                'name' => $name,
                'guard_name' => $guard_name,
            ];

    }
}
