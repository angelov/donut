<?php

namespace SocNet\Behat\Pages\Friendships;

use SocNet\Behat\Pages\Users\UserCard;

class FriendshipSuggestionCard extends UserCard
{
    public function ignore() : void
    {
        $this->getElement()->find('css', '.btn-ignore-friendship-suggestion')->press();
    }
}
