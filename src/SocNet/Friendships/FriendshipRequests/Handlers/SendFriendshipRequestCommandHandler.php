<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class SendFriendshipRequestCommandHandler
{
    private $friendshipRequests;

    public function __construct(FriendshipRequestsRepositoryInterface $friendshipRequests)
    {
        $this->friendshipRequests = $friendshipRequests;
    }

    public function handle(SendFriendshipRequestCommand $command) : void
    {
        $friendshipRequest = new FriendshipRequest(
            $command->getSender(),
            $command->getRecipient()
        );

        $this->friendshipRequests->store($friendshipRequest);
    }

}
