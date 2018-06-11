<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Tests\Api;

use Symfony\Component\HttpFoundation\Response;

class ListingCommunitiesTest extends ApiTestCase
{
    /** @test */
    public function listing_the_communities_as_non_authenticated_user()
    {
        $this->client->request('GET', '/api/communities');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'errors/non_authenticated', Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function listing_the_communities_as_authenticated_user()
    {
        $this->loadFixturesFromFiles([
            'users.yml',
            'communities.yml'
        ]);

        $this->client->request('GET', '/api/communities', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ'
        ]);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'communities/index', Response::HTTP_OK);
    }
}
