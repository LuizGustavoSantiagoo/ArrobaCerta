<?php

namespace Tests\Integration\Controllers;

use App\Models\User;

class CattleControllerTest extends ControllerTestCase
{
    private User $testUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->testUser = new User();
        $this->testUser->name = 'Test User';
        $this->testUser->email = 'test@example.com';
        $this->testUser->encrypted_password = password_hash('password123', PASSWORD_DEFAULT);
        $this->testUser->role = 'manager';
        $this->testUser->status = 'active';
        $this->testUser->save();
        $_SESSION['user']['id'] = $this->testUser->id;
    }

    public function test_render_cattle_index_page(): void
    {
        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\CattleController'
        );

        $this->assertStringContainsString('<h1 class="font-bold">Listagem de vacas</h1>', $response);
    }
}
