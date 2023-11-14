<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_reviews_answers', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_id')
                ->constrained('review_reviews')
                ->onDelete('cascade');

            $table->text('text');

            $table->timestamp('created_at');

            $table->unique(['review_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reviews_answers');
    }
};
