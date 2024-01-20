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
        Schema::create('shop_cart.shop_cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_cart_id');
            $table->unsignedBigInteger('product_offer_id');
            $table->integer('count');
            $table->timestamps();

            $table
                ->foreign('shop_cart_id')
                ->references('id')->on('shop_cart.shop_carts')->cascadeOnDelete();

            $table
                ->foreign('product_offer_id')
                ->references('id')->on('catalog.product_offers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_cart.shop_cart_items');
    }
};
