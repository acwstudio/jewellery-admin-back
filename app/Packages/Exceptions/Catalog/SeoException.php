<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Catalog;

use App\Packages\Exceptions\DomainException;

class SeoException extends DomainException
{
    protected $message = 'Seo exception';
    protected $code = 'catalog-module_seo_exception';
    protected $description = 'Ошибка SEO';

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
