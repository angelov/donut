<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\Controller;

use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use AppBundle\FriendsSuggestions\RecommenderService;
use GraphAware\Neo4j\Client\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FriendsController extends AbstractController
{
    /**
     * @Route("/friends", name="app.friends.index", methods={"GET", "HEAD"})
     */
    public function index(UsersRepositoryInterface $users, Client $neo4jclient) : Response
    {
        $user = $this->getUser();
        $suggestedUsers = [];

        try {
            $recommender = new RecommenderService($neo4jclient);
            $recommendation = $recommender->recommendFriendsForuser($user);

            foreach ($recommendation->getItems() as $item) {
                $suggestedUsers[] = $users->find($item->item()->value('id'));
            }
        } catch (\Exception $e) {
        }

        return $this->render('friends/index.html.twig', [
            'suggested_users' => $suggestedUsers
        ]);
    }
}
