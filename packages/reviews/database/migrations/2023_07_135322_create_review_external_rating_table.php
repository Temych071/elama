<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_external_rating', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_form_id')
                ->constrained('review_forms')
                ->onDelete('cascade');

            $table->string('source', 32);
            $table->date('date');

            $table->unsignedDecimal('rating')
                ->default(0.0);
            $table->unsignedInteger('reviewsCount')
                ->default(0);

            $table->unique(['review_form_id', 'source', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_external_rating');
    }
};
