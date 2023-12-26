<?php

declare(strict_types=1);

namespace App\Packages\Exceptions;

class CircularRelationException extends DomainException
{
    protected $code = 'checkout-back_catalog_module_circular_relation_exception';
    protected $message = 'Circular exception error';
    protected $description = 'Сущность не может быть родителем самого себя';
}
