<?php

declare(strict_types=1);

namespace App\Modules\Storage\Models;

use Database\Factories\Modules\Storage\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 */
class File extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $table = 'storage.files';

    protected static function newFactory()
    {
        return app(FileFactory::class);
    }
}
