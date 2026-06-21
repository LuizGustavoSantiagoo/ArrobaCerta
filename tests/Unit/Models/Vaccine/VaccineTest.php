<?php

namespace Tests\Unit\Models\Vaccine;

use App\Models\Vaccine;
use Core\Database\ActiveRecord\BelongsToMany;
use Tests\TestCase;

class VaccineTest extends TestCase
{
    private Vaccine $vaccine;

    public function setUp(): void
    {
        parent::setUp();

        $this->vaccine = new Vaccine([
            'name' => 'Febre Aftosa Teste',
            'description' => 'Descrição da vacina para testes automatizados'
        ]);
        $this->vaccine->save();
    }

    public function test_should_create_new_vaccine(): void
    {
        $vaccine = Vaccine::findById($this->vaccine->id);
        $this->assertEquals('Febre Aftosa Teste', $vaccine->name);
    }

    public function test_errors_should_return_errors(): void
    {
        $vaccine = new Vaccine();

        $this->assertFalse($vaccine->isValid());
        $this->assertFalse($vaccine->save());
        $this->assertTrue($vaccine->hasErrors());

        $this->assertEquals('não pode ser vazio!', $vaccine->errors('name'));
    }

    public function test_cattle_relationship_returns_belongs_to_many(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->vaccine->cattle());
    }
}
