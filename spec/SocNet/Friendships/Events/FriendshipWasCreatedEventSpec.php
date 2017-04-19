<?php

namespace spec\SocNet\Friendships\Events;

use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\Friendship;

class FriendshipWasCreatedEventSpec extends ObjectBehavior
{
    function let(Friendship $friendship)
    {
        $this->beConstructedWith($friendship);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FriendshipWasCreatedEvent::class);
    }

    function it_holds_the_friendship(Friendship $friendship)
    {
        $this->getFriendship()->shouldReturn($friendship);
    }
}
