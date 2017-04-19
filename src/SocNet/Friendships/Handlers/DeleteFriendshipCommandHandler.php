<?php

namespace SocNet\Friendships\Handlers;

use SocNet\Friendships\Commands\DeleteFriendshipCommand;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

class DeleteFriendshipCommandHandler
{
    private $friendships;

    public function __construct(FriendshipsRepositoryInterface $friendships)
    {
        $this->friendships = $friendships;
    }

    public function handle(DeleteFriendshipCommand $command) : void
    {
        $this->friendships->destroy(
            $command->getFriendship()
        );
    }
}
