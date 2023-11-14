<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Reviews\Models\ReviewComment;

class ReviewCommentFactory extends Factory
{
    protected $model = ReviewComment::class;

    public function definition(): array
    {
        return [
            'review_id' => \Reviews\Models\Review::query()->inRandomOrder()->firstOrFail()->id,
            'user_id' => \Module\User\Models\User::query()->inRandomOrder()->firstOrFail()->id,
            'text' => fake()->text,
        ];
    }
}
