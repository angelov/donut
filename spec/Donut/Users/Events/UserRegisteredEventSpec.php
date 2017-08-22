<?php

namespace spec\Angelov\Donut\Users\Events;

use Angelov\Donut\Users\Events\UserRegisteredEvent;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class UserRegisteredEventSpec extends ObjectBehavior
{
    function let(User $user)
    {
        $this->beConstructedWith($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRegisteredEvent::class);
    }

    function it_holds_the_user(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }
}
