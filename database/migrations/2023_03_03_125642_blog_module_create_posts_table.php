<?php

use Illuminate\Database\Grammar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Grammar::macro('typePost_status', function () {
            return 'blog.post_status';
        });

        DB::unprepared("CREATE TYPE blog.post_status AS ENUM ('draft', 'published', 'archived');");

        Schema::create('blog.posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('content');
            $table->addColumn('post_status', 'status');
            $table->dateTime('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();

            $table
                ->foreign('category_id')
                ->references('id')->on('blog.categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog.posts');
        DB::unprepared("DROP TYPE IF EXISTS blog.post_status");
    }
};
