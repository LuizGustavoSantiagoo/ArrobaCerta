<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $file_name
 * @property string $file_path
 * @property string $type
 * @property int $cattle_id
 * @property int $registered_by_id
 * @property Cattle $cattle
 */

class CattleImages extends Model
{
    protected static string $table = 'cattle_images';
    protected static array $columns =
    [
        'file_name',
        'file_path',
        'type',
        'cattle_id',
        'registered_by_id'
    ];

    public function validates(): void
    {
        Validations::notEmpty('file_name', $this);
        Validations::notEmpty('file_path', $this);
        Validations::notEmpty('type', $this);
        Validations::in('type', $this, ['none', 'death_photo', 'cattle']);
        Validations::notEmpty('cattle_id', $this);
        Validations::notEmpty('registered_by_id', $this);
    }

    public function cattle(): BelongsTo
    {
        return $this->belongsTo(Cattle::class, 'cattle_id');
    }
}
