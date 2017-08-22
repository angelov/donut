<?php

namespace Angelov\Donut\Friendships\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Friendships\Commands\DeleteFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;

class DeleteFriendshipCommandHandler
{
    private $friendships;
    private $eventBus;

    public function __construct(FriendshipsRepositoryInterface $friendships, EventBusInterface $eventBus)
    {
        $this->friendships = $friendships;
        $this->eventBus = $eventBus;
    }

    public function handle(DeleteFriendshipCommand $command) : void
    {
        $friendship = $command->getFriendship();

        $this->friendships->destroy($friendship);

        $this->eventBus->fire(new FriendshipWasDeletedEvent($friendship));
    }
}
