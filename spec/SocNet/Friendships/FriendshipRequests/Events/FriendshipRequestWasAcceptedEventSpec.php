<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Events;

use SocNet\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

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
