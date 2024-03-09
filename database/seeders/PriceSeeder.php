<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('prices')->truncate();
        DB::table('price_categories')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $priceCategories = DB::connection('pgsql_core')->table('catalog.product_offer_prices')->select('type')
            ->distinct()->pluck('type');

        foreach ($priceCategories as $priceCategory) {
            DB::table('price_categories')->insert([
                'name'      => $priceCategory,
                'is_active' => true,
                'slug'      => Str::slug($priceCategory)
            ]);
        }

        $max = 900;
        $total = $this->getQuery()->count();
        $pages = ceil($total / $max);

        for ($i = 1; $i < ($pages + 1); $i++) {
            $offset = (($i - 1) * $max);
            $start = ($offset == 0 ? 0 : ($offset + 1));

            $prices = $this->getQuery()->skip($start)->take($max);

            DB::table('prices')->insert($this->addAttributes($prices));
            dump($max);
        }
    }

    private function getQuery(): Collection
    {
        $ids = DB::table('products')->pluck('core_id')->toArray();
        $string = implode(',', $ids);

        $items = DB::connection('pgsql_core')
            ->select("(select po.product_id, pop.price, pop.type, count(*) as cnt,
            case
                when pop.type = 'regular' then 1
                When pop.type = 'live' then 2
                When pop.type = 'promo' then 3
            end as price_category_id
            from catalog.product_offers as po
            inner join catalog.product_offer_prices pop on po.id = pop.product_offer_id
            inner join catalog.products p on po.product_id = p.id
            where po.product_id in ($string) and  pop.is_active = true and p.is_active = true
            group by po.product_id, pop.price, pop.type)");

        return collect($items);
    }

    private function addAttributes(Collection $items): array
    {
        $items->map(function ($item) {
            $product_id = DB::table('products')->where('core_id', $item->product_id)->first();

            if ($product_id) {
                $item->value = $item->price;
                $item->product_id = $product_id->id;
                $item->is_active = true;
                unset($item->price);
                unset($item->type);
                unset($item->cnt);
            }
        });

        return $items->map(fn($row) => get_object_vars($row))->toArray();
    }
}
