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
        Schema::create('delivery.currier_deliveries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('carrier_id');
            $table->string('price');
            $table->timestamps();

            $table->bigInteger('currier_delivery_address_id');
            $table
                ->foreign('currier_delivery_address_id')
                ->references('id')->on('delivery.currier_delivery_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery.currier_deliveries');
    }
};
