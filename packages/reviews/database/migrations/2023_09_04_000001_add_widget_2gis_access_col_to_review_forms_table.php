<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->boolean('widget_2gis_access')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropColumn(['widget_2gis_access']);
        });
    }
};
