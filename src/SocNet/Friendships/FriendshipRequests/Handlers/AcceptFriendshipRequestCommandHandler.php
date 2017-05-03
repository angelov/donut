<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

class AcceptFriendshipRequestCommandHandler
{
    private $requests;
    private $friendships;
    private $events;
    private $uuidGenerator;

    public function __construct(
        FriendshipRequestsRepositoryInterface $requests,
        FriendshipsRepositoryInterface $friendships,
        UuidGeneratorInterface $uuidGenerator,
        EventBusInterface $events
    ) {
        $this->requests = $requests;
        $this->friendships = $friendships;
        $this->events = $events;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function handle(AcceptFriendshipRequestCommand $command) : void
    {
        $request = $command->getFriendshipRequest();
        $sender = $request->getFromUser();
        $recipient = $request->getToUser();

        $id = $this->uuidGenerator->generate();
        $friendship = new Friendship($id, $sender, $recipient);
        $this->friendships->store($friendship);

        $id = $this->uuidGenerator->generate();
        $friendship = new Friendship($id, $recipient, $sender);
        $this->friendships->store($friendship);

        $this->requests->destroy($request);

        $this->events->fire(new FriendshipRequestWasAcceptedEvent($request));
        $this->events->fire(new FriendshipWasCreatedEvent($friendship));
    }
}
