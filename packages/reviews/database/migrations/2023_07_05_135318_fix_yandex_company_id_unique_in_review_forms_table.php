<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropUnique(['yandex_company_id']);
            $table->unique(['yandex_company_id', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropUnique(['yandex_company_id', 'deleted_at']);
            $table->unique(['yandex_company_id']);
        });
    }
};
