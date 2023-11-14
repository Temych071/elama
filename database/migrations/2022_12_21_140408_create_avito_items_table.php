<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('avito_items', static function (Blueprint $table) {
            $table->bigInteger('id')
                ->index();

            $table->foreignId('source_id')
                ->constrained('sources');

            $table->primary(['id', 'source_id']);


            $table->bigInteger('category_id');

            $table->string('status', 16)
                ->index();

            $table->bigInteger('price')
                ->nullable();

            $table->text('category_name');
            $table->text('title');
            $table->text('url')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avito_items');
    }
};
