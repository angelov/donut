<?php

namespace spec\SocNet\Thoughts\ThoughtsCounter;

use SocNet\Thoughts\ThoughtsCounter\EnsuringDefaultValueThoughtCounter;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use SocNet\Users\User;

class EnsuringDefaultValueThoughtCounterSpec extends ObjectBehavior
{
    function let(ThoughtsCounterInterface $decorated)
    {
        $this->beConstructedWith($decorated);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EnsuringDefaultValueThoughtCounter::class);
    }

    function it_is_thoughts_counter()
    {
        $this->shouldImplement(ThoughtsCounterInterface::class);
    }

    function it_passes_increase_calls_to_decorated_counter(ThoughtsCounterInterface $decorated, User $user)
    {
        $decorated->increase($user)->shouldBeCalled();

        $this->increase($user);
    }

    function it_passes_decrease_calls_to_decorated_counter(ThoughtsCounterInterface $decorated, User $user)
    {
        $decorated->decrease($user)->shouldBeCalled();

        $this->decrease($user);
    }

    function it_returns_counted_value_from_decorated_counter_when_there_is_any(ThoughtsCounterInterface $decorated, User $user)
    {
        $decorated->count($user)->willReturn(2);

        $this->count($user)->shouldReturn(2);
    }

    function it_returns_zero_when_something_wents_wrong_with_counting(ThoughtsCounterInterface $decorated, User $user)
    {
        $decorated->count($user)->willThrow(CouldNotCountThoughtsForUserException::class);

        $this->count($user)->shouldReturn(0);
    }
}
