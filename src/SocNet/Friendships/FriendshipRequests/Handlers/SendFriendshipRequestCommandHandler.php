<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class SendFriendshipRequestCommandHandler
{
    private $friendshipRequests;
    private $uuidGenerator;

    public function __construct(FriendshipRequestsRepositoryInterface $friendshipRequests, UuidGeneratorInterface $uuidGenerator)
    {
        $this->friendshipRequests = $friendshipRequests;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function handle(SendFriendshipRequestCommand $command) : void
    {
        $friendshipRequest = new FriendshipRequest(
            $this->uuidGenerator->generate(),
            $command->getSender(),
            $command->getRecipient()
        );

        $this->friendshipRequests->store($friendshipRequest);
    }

}
