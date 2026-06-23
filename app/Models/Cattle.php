<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use App\Services\ImagesService;
use Core\Database\ActiveRecord\HasMany;
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
 * @property CattleImages[] $photos
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

    public function photos(): HasMany
    {
        return $this->hasMany(CattleImages::class, 'cattle_id');
    }

    public function setRealValueInCents(string $property, string $priceInCents): void
    {
        $priceInCents = trim($priceInCents);

        if (empty($priceInCents)) {
            return;
        }

        $cleanValue = str_replace('.', '', $priceInCents);

        $cleanValue = str_replace(',', '.', $cleanValue);

        $normalizedValue = (int) round((float)$cleanValue * 100);

        if ($normalizedValue < 0) {
            $normalizedValue = 0;
        }

        if ($property === 'sale_value_in_cents') {
            parent::__set('sale_value_in_cents', $normalizedValue);
        }

        if ($property === 'purchase_value_in_cents') {
            parent::__set('purchase_value_in_cents', $normalizedValue);
        }
    }

    public function getPurchaseValueInReais(): string
    {
        $value = (int)$this->purchase_value_in_cents / 100;
        return $this->purchase_value_in_cents = number_format($value, 2, ',', '.');
    }

    public function getSaleValueInReais(): string
    {
        $value = (int)$this->sale_value_in_cents / 100;
        return $this->sale_value_in_cents = number_format($value, 2, ',', '.');
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

    public function cattleImages(): ImagesService
    {
        return new ImagesService($this, ['extension' => ['png', 'jpeg'], 'size' => 2 * 1024 * 1024]);
    }

    public function __set(string $property, mixed $value): void
    {
        if (
            in_array($property, ['purchase_value_in_cents', 'sale_value_in_cents'], true) &&
            is_string($value) &&
            !ctype_digit($value)
        ) {
            $this->setRealValueInCents($property, $value);
            return;
        }

        parent::__set($property, $value);
    }

    public function vaccines(): BelongsToMany
    {
        return new BelongsToMany(
            model: $this,
            related: Vaccine::class,
            pivot_table: 'cattle_vaccines',
            from_foreign_key: 'cattle_id',
            to_foreign_key: 'vaccine_id'
        );
    }
}
