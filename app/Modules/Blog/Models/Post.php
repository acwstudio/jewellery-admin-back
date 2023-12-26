<?php

declare(strict_types=1);

namespace App\Modules\Blog\Models;

use App\Modules\Storage\Models\File;
use App\Packages\Enums\PostStatusEnum;
use Database\Factories\Modules\Blog\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $title
 * @property int $image_id
 * @property int $preview_id
 * @property string $content
 * @property PostStatusEnum $status
 * @property Carbon|null $published_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property boolean $is_main
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'blog.posts';

    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'image_id',
        'preview_id',
        'content',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'is_main',
    ];

    protected $casts = [
        'status' => PostStatusEnum::class,
        'published_at' => 'datetime:Y-m-d\TH:i:sP',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function relatedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'blog.related_posts', 'post_id', 'related_post_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function preview(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    protected static function newFactory()
    {
        return app(PostFactory::class);
    }
}
