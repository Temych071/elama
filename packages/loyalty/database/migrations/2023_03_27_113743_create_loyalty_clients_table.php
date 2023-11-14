<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_clients', static function (Blueprint $table) {
            $table->id();

            $table->string('phone', 32)
                ->unique();

            $table->string('verify_code', 16)
                ->nullable();
            $table->timestamp('verify_code_gen_at')
                ->nullable();
            $table->timestamp('phone_verified_at')
                ->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_clients');
    }
};
