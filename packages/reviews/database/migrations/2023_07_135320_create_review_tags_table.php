<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Reviews\Enums\ReviewSource;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('review_tags', static function (Blueprint $table): void {
            $table->id();

            $table->string('tag', 32)
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_tags');
    }
};
