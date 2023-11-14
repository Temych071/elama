<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_forms', static function (Blueprint $table): void {
            $table->id();

            $table->string('slug', 24)->unique();

            $table->foreignId('project_id')
                ->constrained('campaigns');

            $table->string('name');

            $table->unsignedTinyInteger('min_stars_for_publish')
                ->nullable();
            $table->unsignedTinyInteger('max_stars_for_notification')
                ->nullable();
            $table->unsignedTinyInteger('max_stars_for_messengers')
                ->nullable();

            $table->json('page_settings')->nullable();
            $table->json('phrases')->nullable();
            $table->json('external_aggregators')->nullable();
            $table->json('messengers')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_forms');
    }
};
