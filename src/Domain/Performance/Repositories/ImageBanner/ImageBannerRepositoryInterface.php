<?php

namespace Domain\Performance\Repositories\ImageBanner;

use Domain\Performance\Models\ImageBanner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface ImageBannerRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|ImageBanner;
}
