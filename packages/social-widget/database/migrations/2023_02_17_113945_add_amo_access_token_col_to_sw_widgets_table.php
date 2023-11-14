<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sw_widgets', static function (Blueprint $table) {
            $table->string('amo_domain')
                ->nullable();
            $table->json('amo_access_token')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sw_widgets', static function (Blueprint $table) {
            $table->dropColumn('amo_access_token');
            $table->dropColumn('amo_domain');
        });
    }
};
