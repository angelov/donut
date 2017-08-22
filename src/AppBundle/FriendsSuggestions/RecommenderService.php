<?php

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
