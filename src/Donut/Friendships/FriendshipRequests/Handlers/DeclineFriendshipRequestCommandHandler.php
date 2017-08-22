<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class DeclineFriendshipRequestCommandHandler
{
    private $repository;

    public function __construct(FriendshipRequestsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeclineFriendshipRequestCommand $command) : void
    {
        $this->repository->destroy(
            $command->getFriendshipRequest()
        );
    }
}
