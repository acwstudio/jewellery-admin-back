<?php

declare(strict_types=1);

namespace App\Packages\Support\FilterQuery;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class FilterQueryBuilder
{
    final private function __construct(
        private readonly Builder $query,
        private $filter = null
    ) {
    }

    public static function fromQuery(Builder $query): self
    {
        return new self($query);
    }

    public function withFilter($filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function create(): Builder
    {
        $reflectionClass = new \ReflectionClass($this->filter);

        foreach ($reflectionClass->getProperties() as $property) {
            $field = $property->getName();
            $value = $property->getValue($this->filter);

            if ($field === 'query' && !empty($value)) {
                $this->query->whereRaw((string) $value);
            } elseif ($value instanceof \ArrayAccess || $value instanceof Arrayable) {
                $this->query->whereIn($field, $value);
            } elseif (null !== $value || $this->isAllowedNull($property, $value)) {
                $this->query->where($field, $value);
            }
        }

        return $this->query;
    }


    private function isAllowedNull(\ReflectionProperty $property, $value): bool
    {
        return null === $value && $property->getAttributes(Nullable::class);
    }
}
