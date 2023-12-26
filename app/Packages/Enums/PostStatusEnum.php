<?php

declare(strict_types=1);

namespace App\Packages\Enums;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: PostStatusEnum::PUBLISHED)]
enum PostStatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
