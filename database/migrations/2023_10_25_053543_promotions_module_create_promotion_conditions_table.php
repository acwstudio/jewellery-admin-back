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
        Schema::create('promotions.promotion_conditions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_id');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('finish_at')->nullable();
            $table->string('url_reference')->nullable();
            $table->string('promo_agent')->nullable();
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
        Schema::dropIfExists('promotions.promotion_conditions');
    }
};
