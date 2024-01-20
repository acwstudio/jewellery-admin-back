<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questionnaire.answers', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('question_uuid');
            $table->string('identifier')->comment('Уникальный идентификатор');
            $table->string('value');
            $table->string('comment')->nullable();
            $table->timestamps();

            $table
                ->foreign('question_uuid')
                ->references('uuid')->on('questionnaire.questions')->cascadeOnDelete();

            $table->unique(['question_uuid', 'identifier']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('questionnaire.answers');
    }
};
