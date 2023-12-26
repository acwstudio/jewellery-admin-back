<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $line
 */
class Metro extends Model
{
    use HasFactory;

    protected $table = 'delivery.metro';
    protected $fillable = ['name', 'line'];
    public $timestamps = false;
}
