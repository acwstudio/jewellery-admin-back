<?php

declare(strict_types=1);

namespace App\Modules\Collections\Models;

use Database\Factories\Modules\Collections\StoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Stone extends Model
{
    use HasFactory;

    protected $table = 'collections.stones';

    protected $fillable = [
        'name'
    ];

    protected static function newFactory()
    {
        return app(StoneFactory::class);
    }
}
