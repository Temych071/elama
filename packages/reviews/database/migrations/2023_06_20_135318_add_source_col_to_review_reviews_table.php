<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_reviews', static function (Blueprint $table): void {
            $table->string('source')
                ->default(ReviewSource::DAILY_GROW->value);
            $table->string('external_id')
                ->nullable();

            $table->unique(['review_form_id', 'source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::table('review_reviews', static function (Blueprint $table): void {
            $table->dropUnique(['review_form_id', 'source', 'external_id']);
            $table->dropColumn(['source', 'external_id']);
        });
    }
};
