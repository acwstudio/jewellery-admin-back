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
        Schema::create('users.user_roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('user_id')->on('users.users')->cascadeOnDelete();

            $table
                ->foreign('role_id')
                ->references('id')->on('users.roles')->cascadeOnDelete();

            $table->unique(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users.user_roles');
    }
};
