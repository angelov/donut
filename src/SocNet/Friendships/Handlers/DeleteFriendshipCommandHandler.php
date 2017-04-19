<?php

namespace SocNet\Friendships\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Friendships\Commands\DeleteFriendshipCommand;
use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

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
