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
        Schema::create('promotions.promocode_usages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_benefit_id');
            $table->string('shop_cart_token');
            $table->bigInteger('order_id')->nullable();
            $table->uuid('user_id')->nullable(false);
            $table->boolean('is_active')->nullable(false)->default(false);
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('user_id')->on('users.users')
                ->cascadeOnDelete();

            $table
                ->foreign('promotion_benefit_id')
                ->references('id')->on('promotions.promotion_benefits')
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
        Schema::dropIfExists('promotions.promocode_usages');
    }
};
