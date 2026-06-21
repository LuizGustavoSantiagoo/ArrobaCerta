<?php

namespace Tests\Unit\Models\CattleVaccine;

use App\Models\CattleVaccine;
use Core\Database\ActiveRecord\BelongsTo;
use Tests\TestCase;

class CattleVaccineTest extends TestCase
{
    public function test_errors_should_return_errors(): void
    {
        $cattleVaccine = new CattleVaccine();

        $this->assertFalse($cattleVaccine->isValid());
        $this->assertFalse($cattleVaccine->save());
        $this->assertTrue($cattleVaccine->hasErrors());

        $this->assertEquals('não pode ser vazio!', $cattleVaccine->errors('cattle_id'));
        $this->assertEquals('não pode ser vazio!', $cattleVaccine->errors('vaccine_id'));
        $this->assertEquals('não pode ser vazio!', $cattleVaccine->errors('application_date'));
    }

    public function test_relationships_return_belongs_to(): void
    {
        $cattleVaccine = new CattleVaccine();

        $this->assertInstanceOf(BelongsTo::class, $cattleVaccine->cattle());
        $this->assertInstanceOf(BelongsTo::class, $cattleVaccine->vaccine());
    }
}
