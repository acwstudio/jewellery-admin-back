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
        Schema::create('delivery.user_pvz', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->bigInteger('pvz_id');

            $table
                ->foreign('user_id')
                ->references('user_id')->on('users.users');

            $table
                ->foreign('pvz_id')
                ->references('id')->on('delivery.pvz');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery.user_pvz');
    }
};
