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
        Schema::create('catalog.product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('preview_image_id');
            $table->integer('order_column')->default(1);

            $table
                ->foreign('product_id')
                ->references('id')->on('catalog.products')->cascadeOnDelete();

            $table
                ->foreign('preview_image_id')
                ->references('id')->on('catalog.preview_images')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog.product_images');
    }
};
