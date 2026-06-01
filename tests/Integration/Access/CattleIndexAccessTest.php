<?php

namespace Tests\Integration\Access;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class CattleIndexAccessTest extends TestCase
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

    public function test_should_access_cattle_index_route_not_autheticated(): void
    {
        $response = $this->client->get('/cattle');

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_should_access_cattle_edit_route_not_autheticated(): void
    {
        $response = $this->client->get('/cattle/1/edit');

        $this->assertEquals(302, $response->getStatusCode());
    }
}
