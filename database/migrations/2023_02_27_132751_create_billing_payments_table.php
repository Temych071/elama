<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_payments', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users');

            $table->unsignedFloat('amount');
            $table->string('invoice_uuid', 64)
                ->nullable();
            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_payments');
    }
};
