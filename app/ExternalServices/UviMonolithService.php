<?php

namespace App\ExternalServices;

use App\Exceptions\UserNotFoundException;
use App\Exceptions\ShopcartNotFoundException;
use App\Policies\ShopcartProductsCountStatusPolicy;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

//подключение по апи к монолиту
class UviMonolithService
{
    const DOMAIN = 'https://uvi.ru/api';

    /**
     * @param string $phpSessionId
     *
     * @throws ShopcartNotFoundException
     *
     * @return string
     */
    public function checkProductsCountStatusByShopcart(string $phpSessionId): string
    {
        try {
            $response = Http::withToken(env('UVI_MONOLITH_TOKEN'))
                ->withHeaders([
                    'PHPSESSIONID' => $phpSessionId,
                ])
                ->timeout(2)
                ->get(UviMonolithService::DOMAIN . '/get_products_count_by_shopcart');
        } catch (\Throwable) {
            throw new ShopcartNotFoundException();
        }

        if ($response->status() !== 200) {
            throw new ShopcartNotFoundException();
        }

        $products = json_decode($response->body(), 1)['data'];
        $status = ShopcartProductsCountStatusPolicy::STATUS_ALL_PRODUCTS_EXIST;
        $inStockCount = 0;
        $outOfStockCount = 0;

        if (empty($products)) {
            throw new ShopcartNotFoundException();
        }

        foreach ($products as $product) {
            if ($product['count'] > 0) {
                $inStockCount++;
            }
            if ($product['count'] <= 0) {
                $outOfStockCount++;
            }
        }

        if ($inStockCount == 0 && $outOfStockCount > 0) {
            $status = ShopcartProductsCountStatusPolicy::STATUS_ALL_PRODUCTS_DO_NOT_EXIST;
        }

        if ($inStockCount > 0 && $outOfStockCount > 0) {
            $status = ShopcartProductsCountStatusPolicy::STATUS_SOME_PRODUCTS_DO_NOT_EXIST;
        }

        if ($inStockCount > 0 && $outOfStockCount == 0) {
            $status = ShopcartProductsCountStatusPolicy::STATUS_ALL_PRODUCTS_EXIST;
        }

        return $status;
    }

    /**
     * проверка на существование email в базе монолита
     *
     * @param string $email
     * @param string $phpSessionId
     *
     * @return Response
     */
    public function checkEmailExists(string $email, string $phpSessionId): Response
    {
        $response = Http::withToken(env('UVI_MONOLITH_TOKEN'))
            ->withHeaders([
                'PHPSESSIONID' => $phpSessionId,
            ])
            ->get(UviMonolithService::DOMAIN . '/check_email_exists', [
                'email' => $email
            ]);

        return $response;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return mixed
     */
    public function getAllUserAddresses(int $offset, int $limit): mixed
    {
        $response = Http::withToken(env('UVI_MONOLITH_TOKEN'))
            ->get(UviMonolithService::DOMAIN . '/get_users_addresses', ['offset' => $offset, 'length' => $limit]);
        return json_decode($response->body());
    }

    /**
     * set field dadata_checked on true in monolith base -> table cms_users_addresses
     *
     * @param array $ids
     */
    public function setDadataCheckedToMonolith(array $ids)
    {
        $response = Http::withToken(env('UVI_MONOLITH_TOKEN'))
            ->get(UviMonolithService::DOMAIN . '/set_dadata_checked_fields_to_users_addresses', [
                'ids' => $ids
            ]);
        return json_decode($response->body());
    }

    /**
     * get checkout info from monolith
     *
     * @param string $phpSessionId
     *
     * @throws UserNotFoundException
     *
     * @return mixed
     */
    public function getCheckout(string $phpSessionId): array
    {
        $response = Http::withToken(env('UVI_MONOLITH_TOKEN'))
            ->withHeaders([
                'PHPSESSIONID' => $phpSessionId
            ])
            ->get(UviMonolithService::DOMAIN.'/get_checkout');

        if ($response->status() != 200) {
            throw new UserNotFoundException();
        }

        return json_decode($response->body(), 1);
    }

    public function getOrderNumber1C(int $orderId): string
    {
        return json_decode(
            Http::monolith()->get('/get1cid', ['order_id' => $orderId])->body(),
            1
        )['order_1c_id'];
    }
    public function getOrderProducts(int $orderId): array
    {
        return json_decode(
            Http::monolith()->get('/getOrderProduct', ['order_id' => $orderId])->body(),
            1
        );
    }
}
