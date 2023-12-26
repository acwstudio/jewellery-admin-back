<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GiftCardRepository
{
    /**
     * @return Collection<string>
     */
    public function getGiftCardCycles(): Collection
    {
        return DB::connection('analytics_db')
            ->query()
            ->select('cycle')
            ->distinct()
            ->from('dbo.IP_Gift_card')
            ->pluck('cycle');
    }

    /**
     * @param array|null $cycles
     * @param callable $callback
     * @param int $chunk
     *
     * @return void
     */
    public function eachGiftCardChunk(?array $cycles, callable $callback, int $chunk = 1000): void
    {
        $query = DB::connection('analytics_db')
            ->query()
            ->select([
                'cycle',
                'Card_number as number',
                'Created_date as created_at',
                'Nom as nominal',
                'Sku_id as sku'
            ])
            ->from('dbo.IP_Gift_card');

        if (!is_null($cycles)) {
            $query->whereIn('cycle', $cycles);
        }

        $query->orderBy('number')->chunk(
            $chunk,
            $callback
        );
    }
}
