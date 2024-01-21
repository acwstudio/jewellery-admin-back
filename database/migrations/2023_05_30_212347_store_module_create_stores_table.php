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
        Schema::create('stores.stores', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('Название');
            $table->text('description')->nullable()->comment('Описание');
            $table->string('address')->index()->comment('Адрес');
            $table->decimal('latitude', 10, 8)->comment('Широта');
            $table->decimal('longitude', 11, 8)->comment('Долгота');
            $table->string('phone', 16)->nullable()->comment('Номер телефона магазина');

            $table->boolean('isWorkWeekdays')->default(true)->comment('Работает в будние');
            $table->boolean('isWorkSaturday')->default(false)->comment('Работает в субботу');
            $table->boolean('isWorkSunday')->default(false)->comment('Работает в воскресение');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores.stores');
    }
};
