<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewFormType;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->string('type', 32)
                ->default(ReviewFormType::DEFAULT->value);
        });
    }

    public function down(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropColumn('type');
        });
    }
};
