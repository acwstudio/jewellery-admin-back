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
        Schema::create(
            'vacancies.jobs',
            function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('salary');
                $table->string('city');
                $table->string('experience');
                $table->string('description', 2048);
                $table->integer('department_id')->nullable();

                $table
                    ->foreign('department_id')
                    ->references('id')->on('vacancies.departments')->cascadeOnDelete();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy.jobs');
    }
};
