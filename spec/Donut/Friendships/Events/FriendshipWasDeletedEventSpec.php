<?php

namespace spec\Angelov\Donut\Friendships\Events;

use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\Friendship;

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
