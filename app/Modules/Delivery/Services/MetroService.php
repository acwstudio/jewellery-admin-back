<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Models\Metro;
use App\Modules\Delivery\Repository\MetroRepository;

class MetroService
{
    public function __construct(
        private readonly MetroRepository $metroRepository
    ) {
    }

    public function upsert(string $name, string $line): Metro
    {
        return $this->metroRepository->upsert($name, $line);
    }
}
