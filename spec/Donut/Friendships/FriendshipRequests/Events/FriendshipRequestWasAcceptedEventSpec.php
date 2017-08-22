<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Events;

use Angelov\Donut\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;

class FriendshipRequestWasAcceptedEventSpec extends ObjectBehavior
{
    function let(FriendshipRequest $request)
    {
        $this->beConstructedWith($request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FriendshipRequestWasAcceptedEvent::class);
    }

    function it_holds_the_friendship_request(FriendshipRequest $request)
    {
        $this->getFriendshipRequest()->shouldReturn($request);
    }
}
