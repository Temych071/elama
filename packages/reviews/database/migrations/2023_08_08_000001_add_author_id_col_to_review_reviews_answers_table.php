<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_reviews_answers', static function (Blueprint $table): void {
            $table->timestamp('published_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('review_reviews_answers', static function (Blueprint $table): void {
            $table->dropColumn(['published_at']);
        });
    }
};
