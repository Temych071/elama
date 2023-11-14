<?php

namespace Database\Seeders;

use Database\Factories\ReviewCommentFactory;
use Database\Factories\ReviewFactory;
use Database\Factories\ReviewFormFactory;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        ReviewFormFactory::times(10)->create();

        ReviewFactory::times(50)->create();

        ReviewCommentFactory::times(20)->create();
    }
}
