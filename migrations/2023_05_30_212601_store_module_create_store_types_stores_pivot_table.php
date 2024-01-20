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
        Schema::create('stores.store_types_stores', function (Blueprint $table) {
            $table->integer('store_id');
            $table->integer('store_type_id');

            $table
                ->foreign('store_id')
                ->references('id')->on('stores.stores')->cascadeOnDelete();

            $table
                ->foreign('store_type_id')
                ->references('id')->on('stores.store_types')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores.store_types_stores');
    }
};
