<?php

declare(strict_types=1);

namespace App\Modules\Rules\Models;

use Carbon\Carbon;
use Database\Factories\Modules\Rules\RuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $country
 * @property string $slug
 * @property Carbon $date_start
 * @property Carbon $date_finish
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules.rules';
    protected $fillable = ['title', 'description', 'country', 'date_start', 'date_finish', 'rules', 'slug'];
    protected $dates = [
        'date_start',
        'date_finish'
    ];

    protected $casts = [
        'date_start' => 'date:Y-m-d',
        'date_finish' => 'date:Y-m-d'
    ];

    protected static function newFactory()
    {
        return app(RuleFactory::class);
    }
}
