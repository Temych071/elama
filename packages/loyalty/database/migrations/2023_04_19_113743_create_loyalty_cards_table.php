<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_cards', static function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('loyalty_id')
                ->constrained('loyalty');

            $table->string('card_number');

            $table->timestamp('synced_at')
                ->nullable();

            $table->unique(['loyalty_id', 'card_number']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_cards');
    }
};
