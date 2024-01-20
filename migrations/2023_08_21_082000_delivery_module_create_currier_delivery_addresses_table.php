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
        Schema::dropIfExists('delivery.saved_addresses');

        Schema::create('delivery.currier_delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('zip_code');
            $table->string('region');
            $table->string('settlement')->nullable();
            $table->string('city');
            $table->string('street');
            $table->string('house');
            $table->string('flat')->nullable();
            $table->string('block')->nullable();
            $table->string('fias_region_id');
            $table->string('fias_street_id');
            $table->string('fias_house_id');
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
        Schema::dropIfExists('delivery.currier_delivery_addresses');
    }
};
