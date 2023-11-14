<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->bigInteger('widget_yamaps')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropColumn(['widget_yamaps']);
        });
    }
};
