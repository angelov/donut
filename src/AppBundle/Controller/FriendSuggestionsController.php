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

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Angelov\Donut\Users\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendSuggestionsController extends Controller
{
    /**
     * @Route("/friends/suggestions/{id}/ignore", name="app.friendships.suggestions.ignore")
     */
    public function ignoreAction(User $suggestedUser) : Response
    {
        // @todo refactor

        $query = '
            MATCH 
                (current:User {id: {current}}),
                (suggested:User {id: {suggested}})
            CREATE
                (current)-[:NOT_INTERESTED_TO_BE_FRIEND_WITH]->(suggested)      
        ';

        $client = $this->get('neo4j.client.default');

        $client->run($query, [
            'current' => $this->getUser()->getId(),
            'suggested' => $suggestedUser->getId()
        ]);

        return $this->redirectToRoute('app.friends.index');
    }
}
