<?php

namespace Tests\Unit\Services;

use App\Models\Cattle;
use App\Models\CattleImages;
use App\Models\User;
use Tests\TestCase;

class ImagesServiceTest extends TestCase
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

    public function test_should_not_create_with_invalid_extension(): void
    {
        $file = [
            'name' => 'arquivo.exe',
            'tmp_name' => '/tmp/fake',
            'size' => 1000,
            'error' => UPLOAD_ERR_OK,
        ];

        $result = $this->cattle->cattleImages()->create($file, 'cattle', $this->user->id);

        $this->assertFalse($result);
        $this->assertCount(0, CattleImages::all());
    }

    public function test_should_not_create_with_a_large_file(): void
    {
        $file = [
            'name' => 'foto.png',
            'tmp_name' => '/tmp/fake',
            'size' => 5 * 1024 * 1024,
            'error' => UPLOAD_ERR_OK,
        ];

        $result = $this->cattle->cattleImages()->create($file, 'cattle', $this->user->id);

        $this->assertFalse($result);
    }

    public function test_all_should_return_the_cattle_images(): void
    {
        $image = new CattleImages([
            'file_name' => 'foto.png',
            'file_path' => '/assets/uploads/cattle/' . $this->cattle->id . '/foto.png',
            'type' => 'cattle',
            'cattle_id' => $this->cattle->id,
            'registered_by_id' => $this->user->id,
        ]);
        $image->save();

        $this->assertCount(1, $this->cattle->cattleImages()->all());
    }

    public function test_destroy_should_remove_the_image(): void
    {
        $image = new CattleImages([
            'file_name' => 'foto.png',
            'file_path' => '/assets/uploads/cattle/' . $this->cattle->id . '/foto.png',
            'type' => 'cattle',
            'cattle_id' => $this->cattle->id,
            'registered_by_id' => $this->user->id,
        ]);
        $image->save();

        $this->cattle->cattleImages()->destroy($image);

        $this->assertNull(CattleImages::findById($image->id));
    }
}
