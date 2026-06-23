<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;

/**
 * Model exigido pelo checklist para representar a tabela pivô.
 * @property int $id
 * @property int $cattle_id
 * @property int $vaccine_id
 * @property string $application_date
 */
class CattleVaccine extends Model
{
    protected static string $table = 'cattle_vaccines';
    protected static array $columns = [
        'cattle_id',
        'vaccine_id',
        'application_date'
    ];

    public function validates(): void
    {
        Validations::notEmpty('cattle_id', $this);
        Validations::notEmpty('vaccine_id', $this);
        Validations::notEmpty('application_date', $this);
    }

    public function cattle(): BelongsTo
    {
        return new BelongsTo($this, Cattle::class, 'cattle_id');
    }

    public function vaccine(): BelongsTo
    {
        return new BelongsTo($this, Vaccine::class, 'vaccine_id');
    }
}
