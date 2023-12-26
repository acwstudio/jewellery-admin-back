<?php

declare(strict_types=1);

namespace App\Modules\Stores\Repositories;

use App\Modules\Stores\Models\StoreType;
use Illuminate\Database\Eloquent\Collection;

class StoreTypeRepository
{
    public function getAll(): Collection
    {
        return StoreType::all();
    }

}
