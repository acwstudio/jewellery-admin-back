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
        Schema::table('catalog.products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable();

            $table
                ->foreign('brand_id')
                ->references('id')->on('catalog.brands')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog.products', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }
};
