<?php

namespace Angelov\Donut\Behat\Pages\Friendships;

use Angelov\Donut\Behat\Pages\Users\UserCard;

class FriendshipRequestCard extends UserCard
{
    public function accept() : void
    {
        $this->pressButton('.btn-accept-friendship');
    }

    public function decline() : void
    {
        $this->pressButton('.btn-decline-friendship');
    }

    public function cancel() : void
    {
        $this->pressButton('.btn-cancel-friendship-request');
    }

    private function pressButton(string $class) : void
    {
        $this->getElement()->find('css', $class)->press();
    }
}
