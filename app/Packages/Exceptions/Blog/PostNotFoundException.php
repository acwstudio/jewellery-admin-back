<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Blog;

use App\Packages\Exceptions\DomainException;

class PostNotFoundException extends DomainException
{
    protected $code = 'checkout-back_blog_module_post_not_found_exception';
    protected $message = 'Blog post not found';
    protected $description = 'Пост блога не найден';
}
