<?php

namespace App\Modules\Stores\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subway extends Model
{
    protected $table = 'stores.subways';

    protected $fillable = ['line', 'station', 'color'];

    public function stores():BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'stores.store_subways_stores');
    }
}
