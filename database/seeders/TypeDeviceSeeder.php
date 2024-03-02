<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TypeDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('type_devices')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $typeDevices = [
            'Настольный','мобильный',
        ];

        foreach ($typeDevices as $key => $typeDevice) {
            DB::table('type_devices')->insert([
                'type' => $typeDevice,
                'slug' => Str::slug($typeDevice),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
