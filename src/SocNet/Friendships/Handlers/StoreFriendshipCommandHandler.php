<?php

namespace SocNet\Friendships\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Friendships\Commands\StoreFriendshipCommand;
use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

class StoreFriendshipCommandHandler
{
    private $friendships;
    private $eventBus;

    public function __construct(FriendshipsRepositoryInterface $friendships, EventBusInterface $eventBus)
    {
        $this->friendships = $friendships;
        $this->eventBus = $eventBus;
    }

    public function handle(StoreFriendshipCommand $command)
    {
        $friendship = new Friendship(
            $command->getId(),
            $command->getUser(),
            $command->getFriend()
        );

        $this->friendships->store($friendship);

        $this->eventBus->fire(new FriendshipWasCreatedEvent($friendship));
    }
}
