<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
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

    public function __construct(FriendshipRequestsRepositoryInterface $requests, FriendshipsRepositoryInterface $friendships, EventBusInterface $events)
    {
        $this->requests = $requests;
        $this->friendships = $friendships;
        $this->events = $events;
    }

    public function handle(AcceptFriendshipRequestCommand $command) : void
    {
        $request = $command->getFriendshipRequest();
        $sender = $request->getFromUser();
        $recipient = $request->getToUser();

        $friendship = new Friendship($sender, $recipient);
        $this->friendships->store($friendship);

        $friendship = new Friendship($recipient, $sender);
        $this->friendships->store($friendship);

        $this->requests->destroy($request);

        $this->events->fire(new FriendshipRequestWasAcceptedEvent($request));
        $this->events->fire(new FriendshipWasCreatedEvent($friendship));
    }
}
