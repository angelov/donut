<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class DeclineFriendshipRequestCommandHandler
{
    private $repository;

    public function __construct(FriendshipRequestsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeclineFriendshipRequestCommand $command)
    {
        $this->repository->destroy(
            $command->getFriendshipRequest()
        );
    }
}
