<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        2. Транзакции по бонусной карте
            - организация
            - дата
            - номер чека
            - клиент (id)
            - сумма чека
            - сумма скидки
            - списано бонусов
            - осталось бонусов (клиента можно будет не обновлять отдельными запросами)

        3. Начисление бонусов
            - клиент
            - сколько начислило/списалось
            - осталось бонусов
            - дата
        */
        Schema::create('loyalty_transactions', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('loyalty_card_id')
                ->constrained('loyalty_cards');

            $table->timestamp('date');
            $table->string('type', 32);

            $table->unsignedBigInteger('cheque_cost')
                ->default(0);
            $table->unsignedBigInteger('discount')
                ->default(0);
            $table->bigInteger('bonuses_amount')
                ->default(0);
            $table->unsignedBigInteger('bonuses_left')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
