<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewStatus;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_reviews', static function (Blueprint $table): void {
            $table->string('status', 32)
                ->default(ReviewStatus::NOT_MODERATED->value)
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('review_reviews', static function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }
};
