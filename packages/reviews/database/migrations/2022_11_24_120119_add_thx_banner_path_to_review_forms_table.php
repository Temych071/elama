<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->string('thx_banner_path')
                ->nullable();

            $table->text('thx_banner_link')
                ->nullable();

            $table->text('thx_button_link')
                ->nullable();

            $table->text('banner_link')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('review_forms', static function (Blueprint $table): void {
            $table->dropColumn(['thx_banner_path', 'thx_banner_link', 'thx_button_link']);

            $table->string('banner_link')
                ->nullable()
                ->change();
        });
    }
};
