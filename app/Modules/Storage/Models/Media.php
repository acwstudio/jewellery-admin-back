<?php

declare(strict_types=1);

namespace App\Modules\Storage\Models;

use Database\Factories\Modules\Storage\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * @property int $id
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property int|null $order_column
 */
class Media extends BaseMedia
{
    use HasFactory;

    protected $table = 'storage.media';

    public function getFileName(): string
    {
        return $this->file_name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected static function newFactory()
    {
        return app(MediaFactory::class);
    }
}
