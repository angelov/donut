<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Handlers\SendFriendshipRequestCommandHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Users\User;

class SendFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    const FRIENDSHIP_REQUEST_ID = 'uuid value';

    function let(FriendshipRequestsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendFriendshipRequestCommandHandler::class);
    }

    function it_stores_new_friendship_requests(
        SendFriendshipRequestCommand $command,
        User $sender,
        User $recipient,
        FriendshipRequestsRepositoryInterface $repository
    ) {
        $command->getRecipient()->willReturn($recipient);
        $command->getSender()->willReturn($sender);
        $command->getId()->willReturn(self::FRIENDSHIP_REQUEST_ID);

        $repository->store(Argument::type(FriendshipRequest::class))->shouldBeCalled();

        $this->handle($command);
    }
}
