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
        Schema::table('shop_cart.shop_carts', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->string('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_cart.shop_carts', function (Blueprint $table) {
            $table->string('user_id')->nullable(false)->unique()->change();
        });
    }
};
