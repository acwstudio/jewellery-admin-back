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
     */
    public function up()
    {
        Schema::create('delivery.pvz', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('latitude');
            $table->string('longitude');
            $table->string('work_time')->nullable();
            $table->bigInteger('carrier_id');
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();

            $table
                ->foreign('carrier_id')
                ->on('delivery.carriers')->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery.pvz');
    }
};
