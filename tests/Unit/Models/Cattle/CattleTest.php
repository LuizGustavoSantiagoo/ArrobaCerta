<?php

namespace Tests\Unit\Models\Cattle;

use App\Models\Cattle;
use App\Models\User;
use Tests\TestCase;

class CattleTest extends TestCase
{
    private Cattle $cattle;
    private Cattle $cattle2;
    private Cattle $cattle3;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'manager',
            'status' => 'active'
        ]);
        $this->user->save();

        $this->cattle = new Cattle([
            'breed' => 'Angus',
            'purchase_value_in_cents' => 150000,
            'purchase_date' => '2023-01-15',
            'purchase_type' => 'Leilão',
            'state' => 'sold',
            'registered_by_user_id' => 1
        ]);

        $this->cattle->save();

        $this->cattle2 = new Cattle([
            'breed' => 'jerdau',
            'purchase_value_in_cents' => "2.000,00", //2000*100
            'purchase_date' => '2026-05-15 00:00:00',
            'state' => 'active',
            'registered_by_user_id' => 1
        ]);

        $this->cattle2->save();

        $this->cattle3 = new Cattle([
            'breed' => 'jerdau',
            'purchase_value_in_cents' => "2.000,00", //2000*100
            'purchase_date' => '2026-05-15 00:00:00',
            'state' => 'sold',
            'sale_value_in_cents' => "320000",
            'registered_by_user_id' => 1
        ]);

        $this->cattle3->save();
    }

    public function test_setRealValueInCents_method(): void
    {
        //expect "2.000,00" in cents 200000
        $this->assertEquals(200000, $this->cattle2->purchase_value_in_cents);
    }

    public function test_getPurchaseValueInReais(): void
    {
        $this->assertEquals("2.000,00", $this->cattle2->getPurchaseValueInReais());
    }

    public function test_getSaleValueInReais(): void
    {
        $this->assertEquals("3.200,00", $this->cattle3->getSaleValueInReais());
    }

    public function test_should_create_new_cattle(): void
    {
        $this->assertCount(3, Cattle::all());
    }

    public function test_all_should_return_all_cattle(): void
    {
        $this->cattle2->save();

        $cattleArray[] = $this->cattle->id;
        $cattleArray[] = $this->cattle2->id;
        $cattleArray[] = $this->cattle3->id;

        $all = array_map(fn($cattle) => $cattle->id, Cattle::all());

        $this->assertCount(3, $all);
        $this->assertEquals($cattleArray, $all);
    }

    public function test_if_cattle_state_is_valid(): void
    {
        $cattles = Cattle::all();
        $validStates = ['active', 'sold', 'dead'];

        foreach ($cattles as $cattle) {
            $this->assertContains(
                $cattle->state,
                $validStates,
                "vacas com estado valido."
            );
        }
    }

    public function test_destroy_should_remove_the_cattle(): void
    {
        $this->cattle->destroy();
        $this->assertCount(2, Cattle::all());
    }

    public function test_set_breed(): void
    {
        $this->cattle->breed = 'raça test';
        $this->assertEquals('raça test', $this->cattle->breed);
    }

    public function test_errors_should_return_errors(): void
    {
        $cattle = new Cattle();

        $this->assertFalse($cattle->isValid());
        $this->assertFalse($cattle->save());
        $this->assertTrue($cattle->hasErrors());

        $this->assertEquals('não pode ser vazio!', $cattle->errors('breed'));
        $this->assertEquals('não pode ser vazio!', $cattle->errors('purchase_date'));
        $this->assertEquals('valor inválido!', $cattle->errors('state'));
        $this->assertEquals('não pode ser vazio!', $cattle->errors('registered_by_user_id'));
    }

    public function test_find_by_id_should_return_the_cattle(): void
    {
        $this->assertEquals($this->cattle->id, Cattle::findById($this->cattle->id)->id);
    }

    public function test_find_by_id_should_return_null(): void
    {
        $this->assertNull(Cattle::findById(4));
    }
}
