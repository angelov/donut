<?php

namespace SocNet\Behat\Pages\Friendships;

use SocNet\Behat\Pages\Users\UserCard;

class FriendshipCard extends UserCard
{
    public function delete() : void
    {
        $this->getElement()->find('css', '.btn-delete-friendship')->press();
    }
}
