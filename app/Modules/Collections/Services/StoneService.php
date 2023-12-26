<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Models\Stone;
use App\Modules\Collections\Repository\StoneRepository;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StoneService
{
    public function __construct(
        private readonly StoneRepository $stoneRepository
    ) {
    }

    public function getStone(int $id): ?Stone
    {
        return $this->stoneRepository->getById($id);
    }

    public function getStones(Pagination $pagination): LengthAwarePaginator
    {
        return $this->stoneRepository->getList($pagination);
    }

    public function createStone(string $name): Stone
    {
        return $this->stoneRepository->create($name);
    }

    public function deleteStone(int $id): void
    {
        $stone = $this->stoneRepository->getById($id, true);
        $this->stoneRepository->delete($stone);
    }
}
