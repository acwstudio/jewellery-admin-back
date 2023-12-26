<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Enums;

use Illuminate\Support\Str;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'catalog_feature_type_enum',
    type: 'string'
)]
enum FeatureTypeEnum: string
{
    case INSERT = 'insert';
    case INSERT_COLOR = 'insert_color';
    case METAL = 'metal';
    case METAL_COLOR = 'metal_color';
    case SEX = 'sex';
    case HOROSCOPE = 'horoscope';
    case PROBE = 'probe';
    case COMPLETENESS = 'completeness';
    case DYNAMIC = 'dynamic';
    case COATING = 'coating';
    case COLLECTION = 'collection';
    case SHAPE = 'shape';
    case BOOLEAN = 'boolean';
    case WEAVING = 'weaving';
    case PROVIDER = 'provider';
    case DESIGN = 'design';
    case STYLE = 'style';
    case OCCASION = 'occasion';

    public function getLabel(): string
    {
        return match ($this) {
            self::INSERT => 'Вставка',
            self::INSERT_COLOR => 'Цвет вставки',
            self::METAL => 'Материал',
            self::METAL_COLOR => 'Цвет материала',
            self::SEX => 'Для кого',
            self::HOROSCOPE => 'По гороскопу',
            self::PROBE => 'Проба',
            self::COMPLETENESS => 'Комплектность',
            self::DYNAMIC => 'Динамическое свойство',
            self::COATING => 'Покрытие',
            self::COLLECTION => 'Коллекция',
            self::SHAPE => 'Форма огранки',
            self::BOOLEAN => 'Булевое свойство',
            self::WEAVING => 'Плетение',
            self::PROVIDER => 'Поставщик',
            self::DESIGN => 'Дизайн',
            self::STYLE => 'Стиль',
            self::OCCASION => 'Повод',
        };
    }

    public function getSlug(string $value): string
    {
        $value = Str::slug($value, '_', dictionary: ['.' => '_', ',' => '_']);

        return match ($this) {
            self::INSERT => "s_{$value}om",
            self::METAL => "iz_{$value}",
            self::INSERT_COLOR => "cvet_{$value}",
            self::METAL_COLOR => "{$value}_cveta",
            self::PROBE => "{$value}_probi",
            self::DESIGN => "design_{$value}",
            self::STYLE => "style_{$value}",
            self::OCCASION => "povod_{$value}",
            self::SEX => "sex_{$value}",
            self::HOROSCOPE => "horoscope_{$value}",
            self::COMPLETENESS => "completeness_{$value}",
            self::DYNAMIC => "dynamic_{$value}",
            self::COATING => "coating_{$value}",
            self::COLLECTION => "collection_{$value}",
            self::SHAPE => "shape_{$value}",
            self::BOOLEAN => "boolean_{$value}",
            self::WEAVING => "weaving_{$value}",
            self::PROVIDER => "provider_{$value}"
        };
    }
}
