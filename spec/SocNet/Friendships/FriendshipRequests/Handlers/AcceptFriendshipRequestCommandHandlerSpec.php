<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Handlers;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Handlers\AcceptFriendshipRequestCommandHandler;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;
use SocNet\Users\User;

class AcceptFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(FriendshipRequestsRepositoryInterface $requestsRepository, FriendshipsRepositoryInterface $friendshipsRepository, EventBusInterface $eventBus)
    {
        $this->beConstructedWith($requestsRepository, $friendshipsRepository, $eventBus);
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
        FriendshipsRepositoryInterface $friendshipsRepository,
        EventBusInterface $eventBus
    ) {
        $command->getFriendshipRequest()->willReturn($request);

        $request->getFromUser()->willReturn($sender);
        $request->getToUser()->willReturn($recipient);

        $friendshipsRepository->store(Argument::type(Friendship::class))->shouldBeCalledTimes(2);
        $requestsRepository->destroy($request)->shouldBeCalled();

        $eventBus->fire(Argument::type(FriendshipRequestWasAcceptedEvent::class))->shouldBeCalled();
        $eventBus->fire(Argument::type(FriendshipWasCreatedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
