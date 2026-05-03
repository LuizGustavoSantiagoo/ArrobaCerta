<?php

namespace Tests\Integration\Controllers;

class DashBoardControllerTest extends ControllerTestCase
{
    public function test_render_dashboard_page(): void
    {
        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\DashBoardController'
        );

        $this->assertStringContainsString('<h1 class="text-2xl font-bold mb-4">Dashboard</h1>', $response);
        $this->assertStringContainsString('<p>Bem vindo a pagina inicial ' . $user->name . '.</p>', $response);
    }
}
