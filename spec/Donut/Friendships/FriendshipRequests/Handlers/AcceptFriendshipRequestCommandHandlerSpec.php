<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Handlers\AcceptFriendshipRequestCommandHandler;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use Angelov\Donut\Users\User;

class AcceptFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(
        FriendshipRequestsRepositoryInterface $requestsRepository,
        CommandBusInterface $commandBus,
        UuidGeneratorInterface $uuidGenerator,
        EventBusInterface $eventBus
    ) {
        $this->beConstructedWith($requestsRepository, $uuidGenerator, $commandBus, $eventBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AcceptFriendshipRequestCommandHandler::class);
    }

    function it_handles_accept_friendship_request_commands(
        AcceptFriendshipRequestCommand $command,
        FriendshipRequest $request,
        User $sender,
        User $recipient,
        FriendshipRequestsRepositoryInterface $requestsRepository,
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus
    ) {
        $command->getFriendshipRequest()->willReturn($request);

        $request->getFromUser()->willReturn($sender);
        $request->getToUser()->willReturn($recipient);

        $requestsRepository->destroy($request)->shouldBeCalled();
        $commandBus->handle(Argument::type(StoreFriendshipCommand::class))->shouldBeCalledTimes(2);
        $uuidGenerator->generate()->shouldBeCalledTimes(2);

        $eventBus->fire(Argument::type(FriendshipRequestWasAcceptedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
