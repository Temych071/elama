<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty', static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('project_id')
                ->constrained('campaigns');

            $table->string('name');
            $table->string('api_token');
            $table->unique(['api_token']);

            $table->json('form_settings');
            $table->json('card_settings');

            $table->string('form_logo_path')
                ->nullable();
            $table->string('card_logo_path')
                ->nullable();
            $table->string('card_banner_path')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty');
    }
};
