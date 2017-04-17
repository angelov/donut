<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Handlers\CancelFriendshipRequestCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Users\User;

class CancelFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(FriendshipRequestsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CancelFriendshipRequestCommandHandler::class);
    }

    function it_deletes_friendship_requests(
        CancelFriendshipRequestCommand $command,
        FriendshipRequest $request,
        FriendshipRequestsRepositoryInterface $repository
    ) {
        $command->getFriendshipRequest()->willReturn($request);

        $repository->destroy($request)->shouldBeCalled();

        $this->handle($command);
    }
}
