<?php

namespace App\Modules\Stores\Models;

use Database\Factories\Modules\Store\StoreTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreType extends Model
{
    use HasFactory;

    protected $table = 'stores.store_types';

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return app(StoreTypeFactory::class);
    }
}
