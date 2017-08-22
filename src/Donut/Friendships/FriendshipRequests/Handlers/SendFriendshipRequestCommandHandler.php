<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

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
            $command->getId(),
            $command->getSender(),
            $command->getRecipient()
        );

        $this->friendshipRequests->store($friendshipRequest);
    }

}
