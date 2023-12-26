<?php

declare(strict_types=1);

namespace App\Modules\Monolith\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiService
{
    public function getProductFilters(array $skuArray): iterable
    {
        $body = [
            'sku_ids' => $skuArray
        ];

        /** @var Response $response */
        /** @phpstan-ignore-next-line */
        $response = Http::monolith()->get('/import_product_filter', $body);

        return $response->json() ?? [];
    }

    public function getCollectionProducts(string $name): iterable
    {
        $body = [
            'name' => $name
        ];

        /** @var Response $response */
        /** @phpstan-ignore-next-line */
        $response = Http::monolith()->get('/get_monolith_collections', $body);

        return $response->json() ?? [];
    }

    public function getUsers(): iterable
    {

        /** @var Response $response */
        /** @phpstan-ignore-next-line */
        $response = Http::monolith()->get('/get_monolith_users');

        return $response->json() ?? [];
    }
}
