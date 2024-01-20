<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collections.collection_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('category_id');

            $table
                ->foreign('collection_id')
                ->references('id')->on('collections.collections')->cascadeOnDelete();

            $table
                ->foreign('category_id')
                ->references('id')->on('catalog.categories')->cascadeOnDelete();

            $table->unique(['collection_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::table('collections.collection_categories', function (Blueprint $table) {
            $table->dropUnique(['collection_id', 'category_id']);
        });
        Schema::dropIfExists('collections.collection_categories');
    }
};
