<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Size;

use Domain\Catalog\Repositories\Size\SizeRelationsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;

final class SizeRelationsService
{
    public function __construct(public SizeRelationsRepository $sizeRelationsRepository)
    {
    }

    public function indexSizesProduct(array $data): Model
    {
        return $this->sizeRelationsRepository->indexSizesProduct($data);
    }

    public function indexSizesSizeCategory(array $data): Model
    {
        return $this->sizeRelationsRepository->indexSizesCategorySize($data);
    }

    public function indexSizePrices(array $data): Paginator
    {
        return $this->sizeRelationsRepository->indexSizePrices($data);
    }

    public function indexSizePriceCategories(array $data): Paginator
    {
        return $this->sizeRelationsRepository->indexSizesPriceCategories($data);
    }
}
