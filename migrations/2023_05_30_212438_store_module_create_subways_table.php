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
        Schema::create('stores.subways', function (Blueprint $table) {
            $table->id();

            $table->string('line')->comment('Линия метро');
            $table->string('station')->index()->comment('Название станции');
            $table->string('color', 6)->comment('Цвет линии метро');

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
        Schema::dropIfExists('stores.subways');
    }
};
