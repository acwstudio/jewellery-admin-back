<?php

declare(strict_types=1);

namespace App\Packages\DataCasts;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Exceptions\CannotCastEnum;
use Spatie\LaravelData\Support\DataProperty;

class CollectionCast implements Cast
{
    public function __construct(
        protected ?string $type = null
    ) {
    }

    /**
     * @throws CannotCastEnum
     */
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        $type = $this->type ?? $property->type->findAcceptedTypeForBaseType(Collection::class);

        if (null === $type) {
            return Uncastable::create();
        }

        try {
            /** @var class-string<Collection> $collection */
            return new $type($value);
        } catch (\Throwable $e) {
            throw CannotCastEnum::create($type, $value);
        }
    }
}
