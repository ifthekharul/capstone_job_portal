<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job_details>
 */
class Job_detailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'user_id' => User::inRandomOrder()->first()->id,
           // 'user_id' => rand(1,4),
            'job_type_id' => rand(1,5),
            'category_id' => rand(1,5),
            'vacancy' => rand(1,5),
            'location' => fake()->city(),
            'description' => fake()->text(),
            'experience' => rand(1,10),
            'company_name' => fake()->name(),
        ];
    }
}
