<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;

class AcceptFriendshipRequestCommandSpec extends ObjectBehavior
{
    function let(FriendshipRequest $request)
    {
        $this->beConstructedWith($request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AcceptFriendshipRequestCommand::class);
    }

    function it_holds_the_friendship_request(FriendshipRequest $request)
    {
        $this->getFriendshipRequest()->shouldReturn($request);
    }
}
