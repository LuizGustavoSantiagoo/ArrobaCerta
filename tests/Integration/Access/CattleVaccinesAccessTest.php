<?php

namespace Tests\Integration\Access;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class CattleVaccinesAccessTest extends TestCase
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

    public function test_should_not_access_store_route_not_authenticated(): void
    {
        $response = $this->client->post('/cattle/vaccines/add');

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_should_not_access_destroy_route_not_authenticated(): void
    {
        $response = $this->client->post('/cattle/vaccines/remove');

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_should_not_access_index_route_not_authenticated(): void
    {
        $response = $this->client->get('/vaccines');

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_should_not_access_find_by_name_route_not_authenticated(): void
    {
        $response = $this->client->post('/vaccines/findByName');

        $this->assertEquals(302, $response->getStatusCode());
    }
}
