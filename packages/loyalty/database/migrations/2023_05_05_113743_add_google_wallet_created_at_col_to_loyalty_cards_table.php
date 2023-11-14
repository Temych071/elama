<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty_cards', static function (Blueprint $table) {
            $table->timestamp('google_wallet_created_at')
                ->nullable();
            $table->text('google_wallet_jwt_link')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('loyalty_cards', static function (Blueprint $table) {
            $table->dropColumn([
                'google_wallet_created_at',
                'google_wallet_jwt_link',
            ]);
        });
    }
};
