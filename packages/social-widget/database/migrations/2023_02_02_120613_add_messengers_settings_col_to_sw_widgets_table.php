<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sw_widgets', static function (Blueprint $table): void {
            $table->json('messengers_settings')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sw_widgets', static function (Blueprint $table): void {
            $table->dropColumn('messengers_settings');
        });
    }
};
