<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Blog;

use App\Packages\Exceptions\DomainException;

class CategoryNotFoundException extends DomainException
{
    protected $code = 'checkout-back_blog_module_category_not_found_exception';
    protected $message = 'Blog category not found';
    protected $description = 'Категория блога не найдена';
}
