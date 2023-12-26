<?php

declare(strict_types=1);

namespace App\Modules\Collections\Enums;

enum CollectionImageUrlTypeEnum: string
{
    case PREVIEW = 'preview';
    case PREVIEW_MOB = 'preview_mob';
    case BANNER = 'banner';
    case BANNER_MOB = 'banner_mob';
    case EXTENDED_PREVIEW = 'extended_preview';
}
