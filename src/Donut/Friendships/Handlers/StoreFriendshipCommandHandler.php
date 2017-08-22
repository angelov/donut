<?php

namespace Angelov\Donut\Friendships\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasCreatedEvent;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;

class StoreFriendshipCommandHandler
{
    private $friendships;
    private $eventBus;

    public function __construct(FriendshipsRepositoryInterface $friendships, EventBusInterface $eventBus)
    {
        $this->friendships = $friendships;
        $this->eventBus = $eventBus;
    }

    public function handle(StoreFriendshipCommand $command) : void
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
