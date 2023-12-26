<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Checkout;

use App\Packages\Exceptions\DomainException;

class InsufficientQtyOfGoodsInStockException extends DomainException
{
    protected $code = 'checkout_module_insufficient_qty_of_goods_in_stock_exception';
    protected $message = 'Insufficient quantity of goods in stock';
    protected $description = 'Недостаточное количество товаров на складе. ';

    public function setErrorProducts(array $errorProducts = []): self
    {
        $messages = [];

        foreach ($errorProducts as $errorProduct) {
            $sku = $errorProduct['sku'] ?? '';
            $size = !empty($errorProduct['size']) ? ", размер {$errorProduct['size']}" : '';
            $maxCount = $errorProduct['maxCount'] ?? 0;
            $messages[] = "Товар {$sku}{$size} - доступно {$maxCount}";
        }

        $this->description .= implode('; ', $messages);

        return $this;
    }
}
