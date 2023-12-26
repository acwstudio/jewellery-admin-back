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
        Schema::create('promotions.promotion_reward_gifts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_reward_id');
            $table->string('external_id')->nullable(false);
            $table->string('size')->nullable(false);
            $table->integer('count')->nullable(false);
            $table->timestamps();

            $table
                ->foreign('promotion_reward_id')
                ->references('id')->on('promotions.promotion_rewards')
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
        Schema::dropIfExists('promotions.promotion_reward_gifts');
    }
};
