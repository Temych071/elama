<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty', static function (Blueprint $table) {
            $table->timestamp('google_class_updated_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('loyalty', static function (Blueprint $table) {
            $table->dropColumn(['google_class_updated_at']);
        });
    }
};
