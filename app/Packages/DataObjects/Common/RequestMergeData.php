<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Common;

use Spatie\LaravelData\Data;

class RequestMergeData extends Data
{
    public function merge(): void
    {
        $params = $this->removeNull($this->toArray());
        request()->merge($params);
    }

    private function removeNull(array $array): array
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->removeNull($value);
            } else {
                if (is_null($value)) {
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }
}
