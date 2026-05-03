<?php

namespace Tests\Integration\Controllers;

class HomeControllerTest extends ControllerTestCase
{
    public function test_render_home_page(): void
    {
        $emailInputPattern = '/<input type="text" placeholder="Seu email..." class="bg-gray-100 border rounded-lg p-2" name="email" id="email">/';
        $passwordInputPattern = '/<input type="password" placeholder="Sua senha..." class="bg-gray-100 border rounded-lg p-2" name="password" id="password">/';


        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\HomeController'
        );

        $this->assertMatchesRegularExpression($emailInputPattern, $response);
        $this->assertMatchesRegularExpression($passwordInputPattern, $response);
    }
}
