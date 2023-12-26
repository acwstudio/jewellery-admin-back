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
        Schema::create('orders.orders', function (Blueprint $table) {
            $table->id();
            $table->string('project');
            $table->string('country');
            $table->string('currency');
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->uuid('user_id');

            $table
                ->foreign('user_id')
                ->references('user_id')->on('users.users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders.orders');
    }
};
