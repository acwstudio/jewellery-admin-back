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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_category_id');
            $table->unsignedBigInteger('size_id');
            $table->integer('value');
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('price_category_id')->references('id')->on('price_categories')->cascadeOnDelete();
            $table->foreign('size_id')->references('id')->on('sizes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
};
