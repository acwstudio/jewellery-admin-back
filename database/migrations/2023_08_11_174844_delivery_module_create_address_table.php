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
        Schema::create('delivery.saved_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('postal_code');
            $table->string('fias');
            $table->string('region');
            $table->string('city');
            $table->string('street');
            $table->string('house');
            $table->string('flat');
            $table->uuid('user_id');

            $table
                ->foreign('user_id')
                ->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery.saved_addresses');
    }
};
