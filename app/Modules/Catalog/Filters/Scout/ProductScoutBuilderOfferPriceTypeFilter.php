<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderOfferPriceTypeFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        $types = explode(',', $value);
        $this->priceTypes($builder, $types);
    }

    private function priceTypes(BoolQueryBuilder $builder, array $types): void
    {
        /**
         * TODO Нужно сделать через nested (чтобы сразу два условия срабатывали для одного элемента в массиве)
         * но перед этим надо переделать в индексе тип поля product_offers.product_offer_prices на nested
         */
        $queryGroup = Query::bool();
        $queryGroup->should(Query::terms()->field('product_offers.product_offer_prices.type')->values($types));
        $queryGroup->should(Query::term()->field('product_offers.product_offer_prices.is_active')->value(true));
        $queryGroup->minimumShouldMatch(2);

        $builder->filter($queryGroup);
    }
}
