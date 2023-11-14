<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sw_stats', static function (Blueprint $table): void {
            $table->foreignUuid('widget_id')
                ->constrained('sw_widgets');

            $table->date('date')
                ->index();

            $table->primary(['widget_id', 'date']);

            $table->unsignedBigInteger('views')
                ->default(0);
            $table->unsignedBigInteger('clicks')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sw_stats');
    }
};
