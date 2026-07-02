<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Tag extends Model
{
    protected static string $table = 'tags';
    protected static array $columns = [
        'name',
        'description'
    ];

    public ?string $pivot_application_date = null;
    public ?int $pivot_id = null;

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
    }

    public function cattle(): BelongsToMany
    {
        return new BelongsToMany(
            model: $this,
            related: Cattle::class,
            pivot_table: 'cattle_tags',
            from_foreign_key: 'tag_id',
            to_foreign_key: 'cattle_id'
        );
    }
}
