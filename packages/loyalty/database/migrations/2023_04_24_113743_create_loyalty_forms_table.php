<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_forms', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('loyalty_client_id')
                ->constrained('loyalty_clients');

            $table->foreignId('loyalty_card_id')
                ->constrained('loyalty_cards');

            $table->string('name')
                ->nullable();
            $table->string('surname')
                ->nullable();
            $table->string('email')
                ->nullable();
            $table->string('gender')
                ->nullable();
            $table->timestamp('birthday')
                ->nullable();

            $table->json('custom_fields')
                ->nullable();

            $table->boolean('email_notifications')
                ->default(false);
            $table->boolean('sms_notifications')
                ->default(false);
            $table->boolean('terms_accepted')
                ->default(false);

            $table->unique(['loyalty_client_id', 'loyalty_card_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_forms');
    }
};
