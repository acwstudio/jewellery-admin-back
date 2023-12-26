<?php

namespace App\Packages\Concerns;

use Illuminate\Database\Eloquent\Model;

trait PreventsModelEvents
{
    public static function bootPreventsModelEvents(): void
    {
        foreach (static::$prevents as $event) {
            static::{$event}(function (Model $model) {
                return false;
            });
        }
    }
}
