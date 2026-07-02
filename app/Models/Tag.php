<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;

/**
 * Model exigido pelo checklist para representar a tabela pivô.
 * @property int $id
 * @property string $name
 */
class Tag extends Model
{
    protected static string $table = 'tag';
    protected static array $columns = [
        'name',
    ];

    public ?string $pivot_application_date = null;
    public ?int $pivot_id = null;

    public function cattle(): BelongsToMany
    {
        return new BelongsToMany(
            model: $this,
            related: Cattle::class,
            pivot_table: 'cattle_vaccines',
            from_foreign_key: 'vaccine_id',
            to_foreign_key: 'cattle_id'
        );
    }
}
