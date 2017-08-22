<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class CancelFriendshipRequestCommandHandler
{
    private $requests;

    public function __construct(FriendshipRequestsRepositoryInterface $requests)
    {
        $this->requests = $requests;
    }

    public function handle(CancelFriendshipRequestCommand $command) : void
    {
        $this->requests->destroy(
            $command->getFriendshipRequest()
        );
    }
}
