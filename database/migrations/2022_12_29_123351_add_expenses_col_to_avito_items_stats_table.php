<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('avito_items_stats', static function (Blueprint $table) {
            $table->float('expenses')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('avito_items_stats', static function (Blueprint $table) {
            $table->dropColumn(['expenses']);
        });
    }
};
