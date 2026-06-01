<?php

namespace Tests\Integration\Access;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class CattleImagesAccessTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'allow_redirects' => false,
            'base_uri' => 'http://web:8080'
        ]);
    }

    public function test_should_not_access_create_route_not_authenticated(): void
    {
        $response = $this->client->post('/cattleimages');

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_should_not_access_update_route_not_authenticated(): void
    {
        $response = $this->client->put('/cattleimages/1');

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_should_not_access_destroy_route_not_authenticated(): void
    {
        $response = $this->client->delete('/cattleimages/1');

        $this->assertEquals(302, $response->getStatusCode());
    }
}
