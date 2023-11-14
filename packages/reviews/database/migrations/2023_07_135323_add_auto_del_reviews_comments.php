<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_comments', static function (Blueprint $table): void {
            $table->dropForeign('review_comments_review_id_foreign');
            $table->foreign('review_id')
                ->references('id')->on('review_reviews')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('review_comments', static function (Blueprint $table): void {
            $table->dropForeign('review_comments_review_id_foreign');
            $table->foreign('review_id')
                ->references('id')->on('review_reviews')
                ->onDelete('no action');
        });
    }
};
