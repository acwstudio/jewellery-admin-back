<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypeDevice;

use Domain\AbstractCachedRepository;
use Domain\Performance\Models\TypeDevice;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class TypeDeviceCachedRepository extends AbstractCachedRepository implements TypeDeviceRepositoryInterface
{
    public function __construct(
        public TypeDeviceRepositoryInterface $typeDeviceRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([TypeDevice::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($data) {
                return $this->typeDeviceRepositoryInterface->index($data);
            }
        );
    }

    public function show(int $id, array $data): Model|TypeDevice
    {
        return Cache::tags([TypeDevice::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($id, $data) {
                return $this->typeDeviceRepositoryInterface->show($id, $data);
            }
        );
    }
}
