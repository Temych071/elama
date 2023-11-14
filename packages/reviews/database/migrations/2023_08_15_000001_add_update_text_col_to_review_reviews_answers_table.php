<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_reviews_answers', static function (Blueprint $table): void {
            $table->text('update_text')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('review_reviews_answers', static function (Blueprint $table): void {
            $table->dropColumn(['update_text']);
        });
    }
};
