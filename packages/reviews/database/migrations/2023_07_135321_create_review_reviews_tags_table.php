<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_reviews_tags', static function (Blueprint $table): void {
            $table->foreignId('review_id')
                ->constrained('review_reviews')
                ->onDelete('cascade');

            $table->foreignId('tag_id')
                ->constrained('review_tags')
                ->onDelete('cascade');

            $table->primary(['review_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reviews_tags');
    }
};
