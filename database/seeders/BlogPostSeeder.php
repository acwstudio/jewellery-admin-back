<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Blog\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('blog_posts')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        BlogPost::factory(50)->create();
    }
}
