<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_external_stats', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('review_form_id')
                ->constrained('review_forms');

            $table->string('source', 32);

            $table->date('date');

            $table->unsignedBigInteger('views')
                ->default(0);
            $table->unsignedBigInteger('routes')
                ->default(0);
            $table->unsignedBigInteger('site')
                ->default(0);
            $table->unsignedBigInteger('calls')
                ->default(0);

            $table->unique(['review_form_id', 'source', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_external_stats');
    }
};
