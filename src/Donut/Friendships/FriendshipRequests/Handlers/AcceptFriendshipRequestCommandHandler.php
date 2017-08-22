<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

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
