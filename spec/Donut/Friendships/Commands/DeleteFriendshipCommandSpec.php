<?php

namespace spec\Angelov\Donut\Friendships\Commands;

use Angelov\Donut\Friendships\Commands\DeleteFriendshipCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\Friendship;

class DeleteFriendshipCommandSpec extends ObjectBehavior
{
    function let(Friendship $friendship)
    {
        $this->beConstructedWith($friendship);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteFriendshipCommand::class);
    }

    function it_holds_the_friendship(Friendship $friendship)
    {
        $this->getFriendship()->shouldReturn($friendship);
    }
}
