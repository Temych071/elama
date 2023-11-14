<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->unsignedBigInteger('yandex_company_id')
                ->nullable();

            $table->unique(['yandex_company_id']);
        });
    }

    public function down(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropUnique(['yandex_company_id']);
            $table->dropColumn(['yandex_company_id']);
        });
    }
};
