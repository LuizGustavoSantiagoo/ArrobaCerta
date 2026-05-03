<?php

namespace Tests\Integration\Controllers;

use App\Models\User;

class DashBoardControllerTest extends ControllerTestCase
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

    public function test_render_dashboard_page(): void
    {
        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\DashBoardController'
        );

        $this->assertStringContainsString('<h1 class="text-center font-bold text-xl">Dashboard</h1>', $response);
        $this->assertStringContainsString('<p class="text-center font-light">Bem vindo a pagina inicial ' . $this->testUser->name . '.</p>', $response);
    }
}
