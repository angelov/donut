<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Handlers\DeclineFriendshipRequestCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

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
