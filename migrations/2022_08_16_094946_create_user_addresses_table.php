<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('id')->comment('Уникальный идентификатор');
            $table->integer('external_id')->comment('Уникальный идентификатор адреса пользователя из таблицы монолита');
            $table->integer('user_id')->nullable(false)->comment('Идентификатор пользователя в системе');
            $table->string('kladr', 255)->nullable(true)->comment('Кладр');
            $table->string('region', 255)->nullable(true)->comment('Регион');
            $table->string('city', 255)->nullable(true)->comment('Город');
            $table->integer('zip_code')->nullable(true)->comment('Индекс');
            $table->string('street', 255)->nullable(true)->comment('Улица');
            $table->string('building', 255)->nullable(true)->comment('Дом');
            $table->string('korpus', 255)->nullable(true)->comment('Корпус');
            $table->string('flat', 255)->nullable(true)->comment('Квартира');
            $table->string('pvz_code', 255)->nullable(true)->comment('Признак ПВЗ');
            $table->decimal('latitude', 10, 6)->nullable(true)->comment('Широта');
            $table->decimal('longitude', 10, 6)->nullable(true)->comment('Долгота');
            $table->boolean('is_active')->nullable(false)->comment('Активный ли адрес');
            $table->boolean('dadata_checked')->nullable(false)->comment('Проверен ли адрес дадатой');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
};
