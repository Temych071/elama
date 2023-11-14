<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Module\Billing\Payments\Enums\PaymentMethodStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billing_payment_methods', static function (Blueprint $table) {
            $table->string('status')
                ->default(PaymentMethodStatus::AVAILABLE->value)
                ->change();
        });
    }
};
