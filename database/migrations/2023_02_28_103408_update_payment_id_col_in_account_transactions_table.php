<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_transactions', static function (Blueprint $table): void {
            $table->dropForeign(['payment_id']);
            $table->dropIndex('account_transactions_payment_id_foreign');
            $table->renameColumn('payment_id', 'yookassa_payment_id');
        });

        Schema::table('account_transactions', static function (Blueprint $table): void {
            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('billing_payments');
        });
    }
};
