<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $breed
 * @property string $purchase_value_in_cents
 * @property string $sale_value_in_cents
 * @property string $purchase_date
 * @property string $sale_date
 * @property string $death_date
 * @property string $death_reason
 * @property string $state
 * @property string $purchase_type
 * @property string $registered_by_user_id
 */

class Cattle extends Model
{
    protected static string $table = 'cattle';
    protected static array $columns =
    [
        'breed',
        'purchase_value_in_cents',
        'sale_value_in_cents',
        'purchase_date',
        'sale_date',
        'death_date',
        'death_reason',
        'state',
        'purchase_type',
        'registered_by_user_id'
    ];

    public function validates(): void
    {
        Validations::notEmpty('breed', $this);
        Validations::notEmpty('purchase_date', $this);
        Validations::isNumeric('purchase_value_in_cents', $this);
        Validations::notEmpty('state', $this);
        Validations::in('state', $this, ['active', 'sold', 'dead']);
        Validations::notEmpty('registered_by_user_id', $this);
        if ($this->sale_value_in_cents !== null) {
            Validations::isNumeric('sale_value_in_cents', $this);
            Validations::notEmpty('sale_value_in_cents', $this);
        }

        if ($this->sale_date !== null) {
            Validations::notEmpty('sale_date', $this);
        }
    }

    public function setRealValueInCents(string $priceInCents): int
    {
        $priceInCents = trim($priceInCents);

        if (empty($priceInCents)) {
            return 0;
        }

        $cleanValue = str_replace('.', '', $priceInCents);

        $cleanValue = str_replace(',', '.', $cleanValue);

        $normalizedValue = (int) round((float)$cleanValue * 100);

        if ($normalizedValue < 0) {
            $normalizedValue = 0;
        }

        return $normalizedValue;
    }

    public function getPurchaseValueInReais(): string
    {
        $value = (int)$this->purchase_value_in_cents / 100;
        return number_format($value, 2, ',', '.');
    }

    public function getSaleValueInReais(): string
    {
        $value = (int)$this->sale_value_in_cents / 100;
        return number_format($value, 2, ',', '.');
    }

    public function getStateLabel(): string
    {
        return match ($this->state) {
            'active' => 'Ativo',
            'sold' => 'Vendido',
            'dead' => 'Morto',
            default => 'Ativo'
        };
    }

    public function __set(string $property, mixed $value): void
    {

        if (
            $property === 'purchase_value_in_cents'  &&
            $this->newRecord()
        ) {
            $value = $this->setRealValueInCents($value);
        }

        parent::__set($property, $value);
    }
}
