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
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_category_id');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image_id');
            $table->string('preview_id');
            $table->text('content');
            $table->string('status');
            $table->timestamp('published_at');
            $table->boolean('is_main');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
