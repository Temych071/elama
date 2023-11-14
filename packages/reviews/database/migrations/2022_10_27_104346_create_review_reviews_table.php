<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_reviews', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_form_id')
                ->constrained('review_forms');

            $table->text('comment');
            $table->unsignedTinyInteger('stars');

            $table->string('name')->nullable();
            $table->string('contact')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reviews');
    }
};
