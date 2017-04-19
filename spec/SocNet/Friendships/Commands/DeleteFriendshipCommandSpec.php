<?php

namespace spec\SocNet\Friendships\Commands;

use SocNet\Friendships\Commands\DeleteFriendshipCommand;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\Friendship;

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
