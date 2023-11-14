<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_comments', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_id')
                ->constrained('review_reviews');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->text('text');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_comments');
    }
};
