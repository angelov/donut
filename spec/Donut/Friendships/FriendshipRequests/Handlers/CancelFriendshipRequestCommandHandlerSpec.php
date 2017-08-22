<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Handlers\CancelFriendshipRequestCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use Angelov\Donut\Users\User;

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
