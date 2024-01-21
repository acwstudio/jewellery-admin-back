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
        Schema::create('catalog.categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->string('title');
            $table->string('h1');
            $table->string('description');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_root')->default(false);
            $table->timestamps();

            $table
                ->foreign('parent_id')
                ->references('id')->on('catalog.categories')
                ->onDelete('set null');
        });

        Schema::table('catalog.products', function (Blueprint $table) {
            $table->bigInteger('category_id');

            $table
                ->foreign('category_id')
                ->references('id')->on('catalog.categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('catalog.products', ['category_id']);
        Schema::dropIfExists('catalog.categories');
    }
};
