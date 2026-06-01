<?php

namespace Tests\Unit\Models\CattleImages;

use App\Models\Cattle;
use App\Models\CattleImages;
use App\Models\User;
use Tests\TestCase;

class CattleImagesTest extends TestCase
{
    private User $user;
    private Cattle $cattle;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
            'status' => 'active',
        ]);
        $this->user->save();

        $this->cattle = new Cattle([
            'breed' => 'Nelore',
            'purchase_value_in_cents' => 100000,
            'purchase_date' => '2026-01-10',
            'state' => 'active',
            'registered_by_user_id' => $this->user->id,
        ]);
        $this->cattle->save();
    }

    public function test_should_save_a_valid_image(): void
    {
        $image = new CattleImages([
            'file_name' => 'foto.png',
            'file_path' => '/assets/uploads/cattle/' . $this->cattle->id . '/foto.png',
            'type' => 'cattle',
            'cattle_id' => $this->cattle->id,
            'registered_by_id' => $this->user->id,
        ]);

        $this->assertTrue($image->save());
        $this->assertNotNull(CattleImages::findById($image->id));
    }

    public function test_should_be_invalid_when_empty(): void
    {
        $image = new CattleImages();

        $this->assertFalse($image->isValid());
        $this->assertEquals('não pode ser vazio!', $image->errors('file_name'));
    }

    public function test_type_should_be_a_valid_value(): void
    {
        $image = new CattleImages([
            'file_name' => 'foto.png',
            'file_path' => '/assets/uploads/cattle/' . $this->cattle->id . '/foto.png',
            'type' => 'invalido',
            'cattle_id' => $this->cattle->id,
            'registered_by_id' => $this->user->id,
        ]);

        $this->assertFalse($image->isValid());
        $this->assertEquals('valor inválido!', $image->errors('type'));
    }

    public function test_should_belong_to_a_cattle(): void
    {
        $image = new CattleImages([
            'file_name' => 'foto.png',
            'file_path' => '/assets/uploads/cattle/' . $this->cattle->id . '/foto.png',
            'type' => 'cattle',
            'cattle_id' => $this->cattle->id,
            'registered_by_id' => $this->user->id,
        ]);
        $image->save();

        $this->assertEquals($this->cattle->id, $image->cattle->id);
    }

    public function test_should_not_save_image_for_a_non_existent_cattle(): void
    {
        $this->expectException(\PDOException::class);

        $image = new CattleImages([
            'file_name' => 'foto.png',
            'file_path' => '/assets/uploads/cattle/999/foto.png',
            'type' => 'cattle',
            'cattle_id' => 999,
            'registered_by_id' => $this->user->id,
        ]);
        $image->save();
    }
}
