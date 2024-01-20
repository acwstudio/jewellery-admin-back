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
        Schema::create('collections.collection_stones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('stone_id');
            $table->timestamps();

            $table
                ->foreign('collection_id')
                ->references('id')->on('collections.collections')->cascadeOnDelete();

            $table
                ->foreign('stone_id')
                ->references('id')->on('collections.stones')->cascadeOnDelete();

            $table->unique(['collection_id', 'stone_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections.collection_stones', function (Blueprint $table) {
            $table->dropUnique(['collection_id', 'stone_id']);
        });
        Schema::dropIfExists('collections.collection_stones');
    }
};
