<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_comments', static function (Blueprint $table): void {
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};
