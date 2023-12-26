<?php

declare(strict_types=1);

namespace App\Modules\Blog\Models;

use Database\Factories\Modules\Blog\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property int $position
 * @property string|null $meta_description
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'blog.categories';

    protected $fillable = ['slug', 'name', 'position', 'meta_description'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderby('position');
    }

    protected static function newFactory()
    {
        return app(CategoryFactory::class);
    }
}
