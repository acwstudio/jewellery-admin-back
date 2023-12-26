<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Models;

use Database\Factories\Modules\Vacancies\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 */
class Department extends Model
{
    use HasFactory;

    protected $table = 'vacancies.departments';
    protected $fillable = ['title'];


    protected static function newFactory()
    {
        return app(DepartmentFactory::class);
    }
}
