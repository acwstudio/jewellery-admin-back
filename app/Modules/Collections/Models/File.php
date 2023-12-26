<?php

declare(strict_types=1);

namespace App\Modules\Collections\Models;

use Database\Factories\Modules\Collections\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class File extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = 'collections.files';

    protected static function newFactory()
    {
        return app(FileFactory::class);
    }
}
