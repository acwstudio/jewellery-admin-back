<?php

namespace Domain\Performance\Repositories\TypeDevice;

use Domain\Performance\Models\TypeDevice;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface TypeDeviceRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|TypeDevice;
}
