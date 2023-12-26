<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Repository;

use App\Modules\Delivery\Models\Metro;

class MetroRepository
{
    public function upsert(string $name, string $line): Metro
    {
        /** @var Metro $metro */
        $metro = Metro::query()->updateOrCreate(['name' => $name, 'line' => $line]);
        return $metro;
    }
}
