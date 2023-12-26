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
    public function up()
    {
        Schema::create('payments.apple_pay_payments', function (Blueprint $table) {
            $table->id()->comment('Идентификатор записи в базе данных');
            $table->string('order_number', 32)->nullable()->comment('Номер заказа');
            $table->string('description', 512)->nullable()->comment('Описание заказа');
            $table->string('language', 2)->nullable()->comment('Язык в кодировке ISO 639-1');
            $table->string('additional_parameters', 1024)->nullable()->comment('Дополнительные параметры');
            $table->string('pre_auth', 5)->nullable()->comment('Необходимость предварительной авторизации');
            $table->text('payment_token')->comment('Токен, полученный от Apple Pay');
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
        Schema::dropIfExists('payments.apple_pay_payments');
    }
};
