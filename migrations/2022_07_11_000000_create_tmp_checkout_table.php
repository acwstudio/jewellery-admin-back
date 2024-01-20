<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tmp_checkout_data');

        Schema::create('tmp_checkout', function (Blueprint $table) {
            $table->id()->comment('Уникальный идентификатор');
            $table->string('token')->unique()->nullable(true)->comment('Уникальный токен для идентификации заказа');
            $table->jsonb('data')->nullable(true)->comment('Данные по заказу');
            $table->boolean('is_active')->nullable(true)->default(true)->comment('Признак удаления');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmp_checkout');
    }
};
