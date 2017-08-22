<?php

namespace Angelov\Donut\Behat\Pages\Friendships;

use Angelov\Donut\Behat\Pages\Users\UserCard;

class FriendshipSuggestionCard extends UserCard
{
    public function ignore() : void
    {
        $this->getElement()->find('css', '.btn-ignore-friendship-suggestion')->press();
    }
}
