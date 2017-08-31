<?php

namespace Angelov\Donut\Tests\Api;

use Lakion\ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListingCommunitiesTest extends JsonApiTestCase
{
    /** @test */
    public function listing_the_communities_as_non_authenticated_user()
    {
        $this->markTestSkipped('to do: oauth2 auth');

        $this->client->request('GET', '/api/communities');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'errors/non_authenticated', Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function listing_the_communities_as_authenticated_user()
    {
        // @todo auth

        $this->loadFixturesFromFile('users.yml');
        $this->loadFixturesFromFile('communities.yml');

        $this->client->request('GET', '/api/communities');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'communities/index', Response::HTTP_OK);
    }
}
