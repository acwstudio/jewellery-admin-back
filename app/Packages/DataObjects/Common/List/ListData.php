<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Common\List;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Property;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

abstract class ListData extends Data
{
    public function __construct(
        public readonly DataCollection $items,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data')]
        public readonly PaginationData $pagination
    ) {
    }

    abstract public static function fromPaginator(LengthAwarePaginator $paginator): self;

    protected static function getPaginationData(LengthAwarePaginator $paginator): PaginationData
    {
        return new PaginationData(
            $paginator->currentPage(),
            $paginator->perPage(),
            $paginator->total(),
            $paginator->lastPage()
        );
    }
}
