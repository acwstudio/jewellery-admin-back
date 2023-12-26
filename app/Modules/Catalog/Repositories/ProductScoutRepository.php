<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Contracts\Pipelines\ProductScoutBuilderPipelineContract;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Support\Pipeline\ProductPipelineData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenSearch\ScoutDriverPlus\Decorators\Hit;
use OpenSearch\ScoutDriverPlus\Paginator;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutRepository
{
    public function getById(int $id, bool $fail = false): array
    {
        $paginator = Product::searchQuery()
            ->query(Query::term()->field('id')->value($id))
            ->paginate(perPage: 1, page: 1);

        if ($fail && $paginator->total() === 0) {
            throw (new ModelNotFoundException())->setModel(Product::class);
        }

        /** @var Hit $product */
        $product = $paginator->items()[0];
        return $product->raw()['_source'];
    }

    public function getList(ProductGetListData $data, bool $fail = false): Paginator
    {
        $pagination = $this->setDefaultPagination($data->pagination);

        /** @var ProductScoutBuilderPipelineContract $pipeline */
        $pipeline = app(ProductScoutBuilderPipelineContract::class);

        /** @var ProductPipelineData $pipelineData */
        $pipelineData = $pipeline
            ->send(
                new ProductPipelineData(
                    Product::searchQuery()->trackTotalHits(true),
                    $data
                )
            )
            ->thenReturn();

        $paginator = $pipelineData
            ->builder
            ->paginate(perPage: $pagination->per_page, page: $pagination->page);

        if ($fail && $paginator->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $paginator;
    }

    private function setDefaultPagination(?PaginationData $pagination = null): PaginationData
    {
        $page = $pagination?->page ?? 1;
        $perPage = $pagination?->per_page ?? config('pagination.' . Product::class . '.default_per_page');

        return new PaginationData($page, $perPage);
    }
}
