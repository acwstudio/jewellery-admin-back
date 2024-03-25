<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\Weave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('weaves')->truncate();
        DB::table('product_weave')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        DB::table('products')->where('id', 4)->update([
            'summary' => 'Серебряная цепь 925 пробы; плетение Сингапур;'
        ]);

        DB::table('products')->where('id', 81)->update([
            'summary' => 'Серебряная цепь 925 пробы; плетение Сингапур;'
        ]);

        DB::table('products')->where('id', 136)->update([
            'summary' => 'Серебряная цепь 925 пробы; плетение Сингапур;'
        ]);

        DB::table('products')->where('id', 85)->update([
            'summary' => 'Серебряная цепь 925 пробы; плетение Снейк;'
        ]);

        DB::table('products')->where('id', 95)->update([
            'summary' => 'Серебряная цепь 925 пробы; плетение Снейк;'
        ]);

        DB::table('products')->where('id', 153)->update([
            'summary' => 'Серебряная цепь 925 пробы; плетение Снейк;'
        ]);

        $weaveTypes = [
            '%_ove%','%_исмарк%','%_енецианское%','%_еревка%','%_изантийское%','%_арибальди%','%_урмета%',
            '%_войной _омб%','%_тальянка%','%_априз%','%_ардинал%','%_артье%','%_рис-_рос%','%_он%реаль%','%Нонна%',
            '%_динарный _омб%','%_анцирное%','%_ерлина%','%_итон%','%_опкорн%','%Роза%','%_олло%','%_ингапур%',
            '%_нейк%','%_ройной _омб%','%_антазийное%','%_лоренция%','%_корное%'
        ];
        foreach ($weaveTypes as $weaveType) {
            $name = DB::connection('pgsql_core')->table('catalog.features')->where('value', 'LIKE', $weaveType)->first()->value;
            DB::table('weaves')->insert([
                'name' => $name,
                'slug' => SlugService::createSlug(Weave::class, 'slug', $name),
                'is_active' => true
            ]);
        }

        DB::table('weaves')->insert([
            'name' => 'Улитка',
            'slug' => SlugService::createSlug(Weave::class, 'slug', $name),
            'is_active' => true
        ]);

        DB::table('weaves')->insert([
            'name' => 'Кайзер',
            'slug' => SlugService::createSlug(Weave::class, 'slug', $name),
            'is_active' => true
        ]);

        $weaveTypes[] = "%_литка%";
        $weaveTypes[] = "%_айзер%";

        /** @var Weave $weave */
        foreach ($weaveTypes as $weaveType) {
            dump($weaveType);
//            $name = $weave->name;
            $weaveId = DB::table('weaves')->where('name', 'LIKE', $weaveType)->first()->id;
            $items = DB::table('products')->where('summary', 'LIKE', $weaveType)->get();
//            $items = DB::table('products')->where('summary', 'LIKE', $weaveTypes[1])->get();
//            dump($items);
            foreach ($items as $key => $item) {
//                dump($item->name);
                DB::table('product_weave')->insert([
                    'product_id' => $item->id,
                    'weave_id' => $weaveId,
//                    'thickness' => is_null($productWeave) ? null : $productWeave->value
                ]);
            }
//            unset($weaveType);
//            dd($weaveTypes);
        }
    }
}
