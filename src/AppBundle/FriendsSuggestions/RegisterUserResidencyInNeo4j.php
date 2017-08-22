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

namespace AppBundle\FriendsSuggestions;

use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Users\Events\UserRegisteredEvent;

class RegisterUserResidencyInNeo4j
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function notify(UserRegisteredEvent $event) : void
    {
        $user = $event->getUser();
        $city = $user->getCity();

        $query = '
            MERGE (u:User {id: {userId}, name: {userName}})
            MERGE (c:City {id: {cityId}, name: {cityName}})
            CREATE
                (u)-[:LIVES_IN]->(c)
        ';

        $this->client->run($query, [
            'userId' => $user->getId(),
            'userName' => $user->getName(),
            'cityId' => $city->getId(),
            'cityName' => $city->getName()
        ]);
    }
}
