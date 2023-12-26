<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\Enums;

enum FeedTypeEnum: string
{
    case AVITO = 'avito';
    case VK = 'vk';
    case YANDEX = 'yandex';
    case MINDBOX = 'mindbox';

    public function getPath(): string
    {
        return match ($this) {
            self::AVITO => config('xml_feed.name.avito', 'avito.xml'),
            self::VK => config('xml_feed.name.vk', 'vk.xml'),
            self::YANDEX => config('xml_feed.name.yandex', 'yandex.xml'),
            self::MINDBOX => config('xml_feed.name.mindbox', 'mindbox.xml'),
        };
    }
}
