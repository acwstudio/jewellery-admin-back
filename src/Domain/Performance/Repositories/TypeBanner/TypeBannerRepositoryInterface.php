<?php

namespace Domain\Performance\Repositories\TypeBanner;

use Domain\Performance\Models\TypeBanner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface TypeBannerRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|TypeBanner;
}
