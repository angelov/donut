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
use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\RecommenderService as BaseRecommenderService;
use GraphAware\Reco4PHP\Result\Recommendations;
use Angelov\Donut\Users\User;

class RecommenderService
{
    /**
     * @var BaseRecommenderService
     */
    protected $service;

    public function __construct(Client $client)
    {
        $this->service = BaseRecommenderService::createWithClient($client);
        $this->service->registerRecommendationEngine(new RecommendationEngine());
    }

    public function recommendFriendsForuser(User $user) : Recommendations
    {
        $input = $this->service->findInputBy('User', 'id', $user->getId());
        $recommendationEngine = $this->service->getRecommender('friends_suggestions');

        return $recommendationEngine->recommend($input, new SimpleContext());
    }
}
