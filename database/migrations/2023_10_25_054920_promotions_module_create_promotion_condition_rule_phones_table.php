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
        Schema::create('promotions.promotion_condition_rule_phones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_condition_rule_id');
            $table->string('phone');
            $table->timestamps();

            $table
                ->foreign('promotion_condition_rule_id')
                ->references('id')->on('promotions.promotion_condition_rules')
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
        Schema::dropIfExists('promotions.promotion_condition_rule_phones');
    }
};
