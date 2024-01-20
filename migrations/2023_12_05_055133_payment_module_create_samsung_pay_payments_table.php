<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up(): void
    {
        Schema::create('payments.samsung_pay_payments', static function (Blueprint $table) {
            $table->id()->comment('Идентификатор записи в базе данных');
            $table->string('order_number', 32)->nullable()->comment('Номер заказа');
            $table->string('description', 512)->nullable()->comment('Описание заказа');
            $table->string('language', 2)->nullable()->comment('Язык в кодировке ISO 639-1');
            $table->string('additional_parameters', 1024)->nullable()->comment('Дополнительные параметры');
            $table->string('pre_auth', 5)->nullable()
                ->comment('Параметр, определяющий необходимость предварительной авторизации');
            $table->string('client_id', 255)->nullable()->comment('Номер (идентификатор) клиента в системе продавца');
            $table->string('ip', 39)->nullable()->comment('IP-адрес покупателя');
            $table->text('payment_token')->comment('Токен, полученный от Samsung Pay');
            $table->string('currency_code', 3)->nullable()->comment('Цифровой код валюты платежа ISO 4217');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        Schema::dropIfExists('payments.samsung_pay_payments');
    }
};
