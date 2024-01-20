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
        Schema::dropColumns('catalog.product_features', 'related_feature_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('catalog.product_features', function (Blueprint $table) {
            $table->unsignedBigInteger('related_feature_id')->nullable();

            $table
                ->foreign('related_feature_id')
                ->references('id')->on('catalog.features')->nullOnDelete();
        });
    }
};
