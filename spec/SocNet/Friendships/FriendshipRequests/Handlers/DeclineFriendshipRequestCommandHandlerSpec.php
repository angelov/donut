<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Handlers\DeclineFriendshipRequestCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class DeclineFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(FriendshipRequestsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeclineFriendshipRequestCommandHandler::class);
    }

    function it_deletes_friendship_requests(DeclineFriendshipRequestCommand $command, FriendshipRequest $request, FriendshipRequestsRepositoryInterface $repository)
    {
        $command->getFriendshipRequest()->willReturn($request);

        $repository->destroy($request)->shouldBeCalled();

        $this->handle($command);
    }
}
