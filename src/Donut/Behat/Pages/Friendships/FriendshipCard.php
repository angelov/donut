<?php

namespace Angelov\Donut\Behat\Pages\Friendships;

use Angelov\Donut\Behat\Pages\Users\UserCard;

class FriendshipCard extends UserCard
{
    public function delete() : void
    {
        $this->getElement()->find('css', '.btn-delete-friendship')->press();
    }
}
