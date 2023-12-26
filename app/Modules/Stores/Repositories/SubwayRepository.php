<?php

namespace App\Modules\Stores\Repositories;

use App\Modules\Stores\Models\Subway;
use Illuminate\Database\Query\Builder;

class SubwayRepository
{
    public function getByStation(string $station, bool $fail = false): ?Subway
    {
        /** @var Subway|Builder $query */
        $query = Subway::query()->where('station', $station);

        if ($fail) {
            return $query->firstOrFail();
        }
        return $query->first();
    }
}
