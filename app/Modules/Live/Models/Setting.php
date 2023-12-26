<?php

declare(strict_types=1);

namespace App\Modules\Live\Models;

use App\Modules\Live\Enums\SettingNameEnum;
use Database\Factories\Modules\Live\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property SettingNameEnum $name
 * @property string $value
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'live.settings';

    protected $fillable = ['name', 'value'];

    protected $casts = [
        'name' => SettingNameEnum::class,
    ];

    protected static function newFactory()
    {
        return app(SettingFactory::class);
    }
}
