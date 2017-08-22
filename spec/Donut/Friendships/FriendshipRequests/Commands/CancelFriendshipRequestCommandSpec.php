<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;

class CancelFriendshipRequestCommandSpec extends ObjectBehavior
{
    function let(FriendshipRequest $friendshipRequest)
    {
        $this->beConstructedWith($friendshipRequest);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CancelFriendshipRequestCommand::class);
    }

    function it_holds_the_friendship_request(FriendshipRequest $friendshipRequest)
    {
        $this->getFriendshipRequest()->shouldReturn($friendshipRequest);
    }
}
