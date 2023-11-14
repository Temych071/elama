<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('review_answer_templates');
        Schema::create('review_answer_templates', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('campaigns')
                ->onDelete('cascade');

            $table->string('name');
            $table->text('text');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_answer_templates');
        Schema::create('review_answer_templates', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_form_id')
                ->constrained('review_forms')
                ->onDelete('cascade');

            $table->string('name');
            $table->text('text');

            $table->timestamps();
        });
    }
};
