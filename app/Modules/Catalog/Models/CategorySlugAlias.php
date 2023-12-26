<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $slug
 * @property Category $category
 */
class CategorySlugAlias extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'catalog.category_aliases';
    protected $fillable = ['slug'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
