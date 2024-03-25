<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Product\Relationships;

use Domain\AbstractRelationshipsService;
use Domain\Catalog\Repositories\Product\Relationships\ProductsBlogPostsRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\NoReturn;

final class ProductsBlogPostsRelationshipsService extends AbstractRelationshipsService
{
    public function __construct(protected ProductsBlogPostsRelationshipsRepository $repository)
    {
    }

    public function index(array $params): LengthAwarePaginator|Model
    {
        return $this->repository->index($params);
    }

    public function update(array $data): void
    {
        $this->repository->update($data);
    }
}
