<?php

namespace spec\Angelov\Donut\Friendships\Commands;

use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Users\User;

class StoreFriendshipCommandSpec extends ObjectBehavior
{
    const FRIENDSHIP_ID = 'uuid value';

    function let(User $user, User $friend)
    {
        $this->beConstructedWith(self::FRIENDSHIP_ID, $user, $friend);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreFriendshipCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::FRIENDSHIP_ID);
    }

    function it_holds_the_user(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }

    function it_holds_the_friend(User $friend)
    {
        $this->getFriend()->shouldReturn($friend);
    }
}
