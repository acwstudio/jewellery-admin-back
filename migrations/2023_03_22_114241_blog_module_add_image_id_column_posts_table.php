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
        Schema::table('blog.posts', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable();

            $table
                ->foreign('image_id')
                ->references('id')->on('storage.files')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog.posts', function (Blueprint $table) {
            $table->dropColumn('image_id');
        });
    }
};
