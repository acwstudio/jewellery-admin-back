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
        Schema::table('catalog.product_features', function (Blueprint $table) {
            $table->uuid('parent_uuid')->nullable()->after('feature_id');

            $table
                ->foreign('parent_uuid')
                ->references('uuid')->on('catalog.product_features')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('catalog.product_features', 'parent_uuid');
    }
};
