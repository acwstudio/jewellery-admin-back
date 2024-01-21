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
        Schema::create('promotions.promotion_rewards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_id');
            $table->string('type');
            $table->string('promocode');
            $table->string('nominal_amount')->nullable();
            $table->integer('percent_amount')->unsigned()->nullable();
            $table->string('max_nominal_amount')->nullable();
            $table->integer('use_count')->nullable();
            $table->boolean('is_free_delivery')->nullable();
            $table->boolean('is_gift')->nullable();
            $table->boolean('is_gift_from_shop_cart')->nullable();
            $table->integer('gift_from_shop_cart_count')->nullable();
            $table->timestamps();

            $table
                ->foreign('promotion_id')
                ->references('id')->on('promotions.promotions')
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
        Schema::dropIfExists('promotions.promotion_rewards');
    }
};
