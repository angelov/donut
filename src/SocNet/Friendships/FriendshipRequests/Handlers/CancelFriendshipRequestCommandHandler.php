<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

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
