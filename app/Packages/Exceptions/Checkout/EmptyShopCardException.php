<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Checkout;

use App\Packages\Exceptions\DomainException;

class EmptyShopCardException extends DomainException
{
    protected $code = 'checkout_module_empty_shop_cart_exception';
    protected $message = 'Empty ShopCart';
    protected $description = 'Корзина не может быть пустой при оформлении заказа';
}
