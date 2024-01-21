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
        Schema::create('delivery.metro_pvz', function (Blueprint $table) {
            $table->bigInteger('pvz_id');
            $table->bigInteger('metro_id');

            $table
                ->foreign('pvz_id')
                ->references('id')->on('delivery.pvz');

            $table
                ->foreign('metro_id')
                ->references('id')->on('delivery.metro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery.metro_pvz');
    }
};
