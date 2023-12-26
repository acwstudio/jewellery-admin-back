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
        Schema::create('stores.store_subways_stores', function (Blueprint $table) {
            $table->integer('store_id');
            $table->integer('subway_id');
            $table->float('distance')->nullable()->comment('Расстояние до магазина');

            $table
                ->foreign('store_id')
                ->references('id')->on('stores.stores')->cascadeOnDelete();

            $table
                ->foreign('subway_id')
                ->references('id')->on('stores.subways')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores.store_subways_stores');
    }
};
