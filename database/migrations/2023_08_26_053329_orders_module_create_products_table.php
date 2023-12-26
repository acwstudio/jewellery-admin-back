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
        Schema::create('orders.products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_offer_id');
            $table->string('guid');
            $table->string('sku');
            $table->integer('count');
            $table->string('price');
            $table->string('discount')->nullable();
            $table->string('amount');
            $table->string('size')->nullable();

            $table->bigInteger('order_id');
            $table->timestamps();

            $table
                ->foreign('order_id')
                ->references('id')->on('orders.orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders.products');
    }
};
