<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Blog\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('blog_categories')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        BlogCategory::factory(5)->create();
    }
}
