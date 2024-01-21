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
        Schema::create('catalog.product_features', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('related_feature_id')->nullable();
            $table->integer('count')->nullable();
            $table->timestamps();

            $table
                ->foreign('product_id')
                ->references('id')->on('catalog.products')->cascadeOnDelete();

            $table
                ->foreign('feature_id')
                ->references('id')->on('catalog.features')->cascadeOnDelete();

            $table
                ->foreign('related_feature_id')
                ->references('id')->on('catalog.features')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog.product_features');
    }
};
