<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Handlers;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use SocNet\Core\CommandBus\CommandBusInterface;
use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\Commands\StoreFriendshipCommand;
use SocNet\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Handlers\AcceptFriendshipRequestCommandHandler;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Users\User;

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
