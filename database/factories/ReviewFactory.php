<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Reviews\Models\Review;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'review_form_id' => \Reviews\Models\ReviewForm::query()->inRandomOrder()->firstOrFail()?->id,
            'comment' => fake()->text(),
            'stars' => random_int(1, 5),
            'name' => fake()->name(),
            'contact' => fake()->companyEmail(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
