<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('products')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $chains = $this->getQuery('цеп')->get();
        DB::table('products')->insert($this->addAttributes($chains, 16));

        $bracelets = $this->getQuery('браслет')->get();
        DB::table('products')->insert($this->addAttributes($bracelets, 4));

        $necklaces = $this->getQuery('колье')->get();
        DB::table('products')->insert($this->addAttributes($necklaces, 12));

        $brooches = $this->getQuery('брошь ')->get();
        DB::table('products')->insert($this->addAttributes($brooches, 20));

        $pendants = $this->getQuery('подвеск')->get();
        DB::table('products')->insert($this->addAttributes($pendants, 15));

        $pendants = $this->getQuery('бусы')->get();
        DB::table('products')->insert($this->addAttributes($pendants, 11));

        $max = 600;
        $total = $this->getQuery('кольц')->count();
        $pages = ceil($total / $max);

        for ($i = 1; $i < ($pages + 1); $i++) {
            $offset = (($i - 1) * $max);
            $start = ($offset == 0 ? 0 : ($offset + 1));

            $rings = $this->getQuery('кольц')->skip($start)->take($max)->get();
            DB::table('products')->insert($this->addAttributes($rings, 3));
            dump($max);
        }

        $max = 700;
        $total = $this->getQuery('серьг')->count();
        $pages = ceil($total / $max);

        for ($i = 1; $i < ($pages + 1); $i++) {
            $offset = (($i - 1) * $max);
            $start = ($offset == 0 ? 0 : ($offset + 1));

            $earrings = $this->getQuery('серьг')->skip($start)->take($max)->get();
            DB::table('products')->insert($this->addAttributes($earrings, 6));
            dump($max);
        }
    }

    private function addAttributes(Collection $items, int $id): array
    {
        $collection = $items->map(function ($ring) use ($id) {
            $ring->product_category_id = $id;
            $ring->brand_id = null;
            $ring->slug = Str::slug($ring->name) . '-' . $ring->sku;
            $ring->is_active = true;
            $ring->weight = null;
        });

        return $items->map(fn($row) => get_object_vars($row))->toArray();
    }

    private function getQuery(string $pattern): Builder
    {
        $upperPattern = Str::ucfirst($pattern);
//        dd($upperPattern);
        return $chains = DB::connection('pgsql_core')
            ->table('catalog.products')->select(['sku', 'name', 'summary', 'description'])
            ->where('is_active', true)
            ->where('summary', 'NOT LIKE', '%на цепи%')
            ->where('summary', 'NOT LIKE', '%с подвеской%')
            ->where(function ($query) use ($pattern, $upperPattern) {
                $query->where('summary', 'LIKE', '%' . $pattern . '%')
                    ->orWhere('summary', 'LIKE', '%' . $pattern)
                    ->orWhere('summary', 'LIKE', $upperPattern . '%');
            });
//            ->where('summary', 'LIKE', '%' . $pattern . '%')
//            ->orWhere('summary', 'LIKE', '%' . $pattern)
//            ->orWhere('summary', 'LIKE', $upperPattern . '%');
//            ->orWhere('summary', 'LIKE', '%' . $pattern . '%');
    }
}
