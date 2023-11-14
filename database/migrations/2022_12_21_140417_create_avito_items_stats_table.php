<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('avito_items_stats', static function (Blueprint $table) {
            $table->foreignId('source_id')
                ->constrained('sources');

            $table->bigInteger('item_id')
                ->index();

            $table->date('date')
                ->index();

            $table->primary(['source_id', 'item_id', 'date']);

            $table->bigInteger('views')
                ->default(0);
            $table->bigInteger('favorites')
                ->default(0);
            $table->bigInteger('contacts')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avito_items_stats');
    }
};
