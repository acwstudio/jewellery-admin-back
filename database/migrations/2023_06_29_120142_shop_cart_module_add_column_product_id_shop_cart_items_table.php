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
        Schema::table('shop_cart.shop_cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('shop_cart_id');

            $table
                ->foreign('product_id')
                ->references('id')->on('catalog.products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_cart.shop_cart_items', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
};
