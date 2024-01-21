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
        Schema::create('users.wishlist_products', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('user_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('user_id')->on('users.users')->cascadeOnDelete();

            $table
                ->foreign('product_id')
                ->references('id')->on('catalog.products')->cascadeOnDelete();

            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users.wishlist_products');
    }
};
