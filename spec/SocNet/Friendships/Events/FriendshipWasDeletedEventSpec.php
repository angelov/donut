<?php

namespace spec\SocNet\Friendships\Events;

use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Friendships\Friendship;

class FriendshipWasDeletedEventSpec extends ObjectBehavior
{
    function let(Friendship $friendship)
    {
        $this->beConstructedWith($friendship);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FriendshipWasDeletedEvent::class);
    }

    function it_holds_the_friendship(Friendship $friendship)
    {
        $this->getFriendship()->shouldReturn($friendship);
    }
}
