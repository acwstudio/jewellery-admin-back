<?php

declare(strict_types=1);

namespace App\Modules\Live\Support;

class Pagination
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $perPage = null
    ) {
    }
}
