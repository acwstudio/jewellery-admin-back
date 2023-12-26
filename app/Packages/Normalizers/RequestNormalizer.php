<?php

declare(strict_types=1);

namespace App\Packages\Normalizers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Spatie\LaravelData\Normalizers\Normalizer;

class RequestNormalizer implements Normalizer
{
    public function normalize(mixed $value): ?array
    {
        if (!$value instanceof Request) {
            return null;
        }

        $array = [];
        if ($value->route() instanceof Route) {
            $array = $value->route()->parameters();
        }

        return array_merge($value->toArray(), $array);
    }
}
