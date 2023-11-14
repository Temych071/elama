<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sw_widgets', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignId('project_id')
                ->constrained('campaigns');

            $table->string('name');

            $table->json('view_settings')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sw_widgets');
    }
};
