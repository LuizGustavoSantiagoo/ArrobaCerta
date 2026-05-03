<?php

namespace Tests\Integration\Controllers;

class HomeControllerTest extends ControllerTestCase
{
    public function test_render_home_page(): void
    {
        $emailInputPattern = '/<input required type="text" placeholder="Seu email..." >/';
        $passwordInputPattern = '/<input required type="password" placeholder="Sua senha...">/';


        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\HomeController'
        );

        $this->assertMatchesRegularExpression($emailInputPattern, $response);
        $this->assertMatchesRegularExpression($passwordInputPattern, $response);
    }
}
