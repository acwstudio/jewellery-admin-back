<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Enums;

use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'catalog_feature_dynamic_type_enum',
    type: 'string'
)]
enum FeatureDynamicTypeEnum: string
{
    case COUNT = 'count';
    case WEIGHT = 'weight';
    case AVG_WEIGHT = 'avg_weight';
    case LENGTH_HEIGHT = 'length_height';
    case STONE_SIZE = 'stone_size';
    case METAL_AVG_WEIGHT = 'metal_avg_weight';
    case INSERT_AVG_WEIGHT = 'insert_avg_weight';
    case INSERT_COUNT = 'insert_count';
    case BRACELET_CHAIN_THICK = 'bracelet_chain_thick';
    case RING_SHANG_WIDTH = 'ring_shang_width';

    public function getLabel(): string
    {
        return match ($this) {
            self::COUNT => 'Количество',
            self::WEIGHT => 'Вес',
            self::AVG_WEIGHT => 'Средний вес',
            self::LENGTH_HEIGHT => 'Размеры изделия',
            self::STONE_SIZE => 'Размеры камня',
            self::METAL_AVG_WEIGHT => 'Средний вес металла',
            self::INSERT_AVG_WEIGHT => 'Средний вес вставок',
            self::INSERT_COUNT => 'Количество камней',
            self::BRACELET_CHAIN_THICK => 'Толщина цепи браслета',
            self::RING_SHANG_WIDTH => 'Ширина шинки кольца',
        };
    }
}
