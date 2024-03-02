<?php

namespace Domain\Performance\Repositories\Banner;

use Domain\Performance\Models\Banner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface BannerRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|Banner;
}
