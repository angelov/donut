<?php

namespace SocNet\Friendships\EventSubscribers;

use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

class DeleteInverseFriendship
{
    private $friendships;

    public function __construct(FriendshipsRepositoryInterface $friendships)
    {
        $this->friendships = $friendships;
    }

    public function notify(FriendshipWasDeletedEvent $event) : void
    {
        $friendship = $event->getFriendship();
        $user = $friendship->getUser();
        $friend = $friendship->getFriend();

        $inverse = $this->friendships->findBetweenUsers($user, $friend)[0];

        $this->friendships->destroy($inverse);
    }
}
