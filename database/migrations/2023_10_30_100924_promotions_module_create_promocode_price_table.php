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
        Schema::create('promotions.promocode_prices', function (Blueprint $table) {
            $table->id();
            $table->string('shop_cart_token');
            $table->bigInteger('product_offer_id');
            $table->bigInteger('promotion_benefit_id');
            $table->string('price');
            $table->timestamps();

            $table
                ->foreign('promotion_benefit_id')
                ->on('promotions.promotion_benefits')->references('id')
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
        Schema::dropIfExists('promotions.promocode_prices');
    }
};
