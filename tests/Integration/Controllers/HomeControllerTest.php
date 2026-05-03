<?php

namespace Tests\Integration\Controllers;

class HomeControllerTest extends ControllerTestCase
{
    public function test_render_home_page(): void
    {
        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\HomeController'
        );

        $this->assertMatchesRegularExpression('/<input required type="text" placeholder="Seu email..." class="bg-neutral-secondary-medium border border-default-medium rounded-lg text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" name="email" id="email">/', $response);
        $this->assertMatchesRegularExpression('/<input required type="password" placeholder="Sua senha..." class="bg-neutral-secondary-medium border border-default-medium rounded-lg text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" name="password" id="password">/', $response);
    }
}
