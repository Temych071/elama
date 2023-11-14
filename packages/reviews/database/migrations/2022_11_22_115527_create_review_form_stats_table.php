<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_form_stats', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_form_id')
                ->constrained('review_forms');

            $table->date('date')
                ->index();

            $table->unsignedBigInteger('views')
                ->default(1);

            $table->unique(['review_form_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_form_stats');
    }
};
