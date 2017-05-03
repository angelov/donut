<?php

namespace SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Core\CommandBus\CommandBusInterface;
use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\Commands\StoreFriendshipCommand;
use SocNet\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class AcceptFriendshipRequestCommandHandler
{
    private $requests;
    private $events;
    private $uuidGenerator;
    private $commandBus;

    public function __construct(
        FriendshipRequestsRepositoryInterface $requests,
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus,
        EventBusInterface $events
    ) {
        $this->requests = $requests;
        $this->events = $events;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
    }

    public function handle(AcceptFriendshipRequestCommand $command) : void
    {
        $request = $command->getFriendshipRequest();
        $sender = $request->getFromUser();
        $recipient = $request->getToUser();

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $sender, $recipient));

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $recipient, $sender));

        $this->requests->destroy($request);

        $this->events->fire(new FriendshipRequestWasAcceptedEvent($request));
    }
}
