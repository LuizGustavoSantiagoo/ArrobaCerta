<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;

/**
 * Model exigido pelo checklist para representar a tabela pivô.
 * @property int $id
 * @property int $cattle_id
 * @property int $tag_id
 * @property string $application_date
 */
class CattleTag extends Model
{
    protected static string $table = 'cattle_tags';
    protected static array $columns = [
        'cattle_id',
        'tag_id',
        'application_date'
    ];

    public function validates(): void
    {
        Validations::notEmpty('cattle_id', $this);
        Validations::notEmpty('tag_id', $this);
        Validations::notEmpty('application_date', $this);
    }

    public function cattle(): BelongsTo
    {
        return new BelongsTo($this, Cattle::class, 'cattle_id');
    }

    public function tag(): BelongsTo
    {
        return new BelongsTo($this, Tag::class, 'tag_id');
    }
}
