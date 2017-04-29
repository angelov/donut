<?php

namespace AppBundle\FriendsSuggestions;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;

class RecommendationEngine extends BaseRecommendationEngine
{
    public function name(): string
    {
        return 'friends_suggestions';
    }

    public function discoveryEngines() : array
    {
        return array(
            new FriendsOfFriends(),
            new LivingInSameCity()
        );
    }

    public function blacklistBuilders() : array
    {
        return array(
            new FriendsOfMine(),
            new IgnoredSuggestions()
        );
    }

    public function filters() : array
    {
        return array(
            new ExcludeCurrentUser()
        );
    }
}
