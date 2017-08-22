<?php

namespace spec\Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions;

use RuntimeException;
use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class CouldNotCountThoughtsForUserExceptionSpec extends ObjectBehavior
{
    const REASON_MESSAGE = 'Something went wrong';

    function let(User $user)
    {
        $this->beConstructedWith($user, self::REASON_MESSAGE);

        $user->getId()->willReturn('2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CouldNotCountThoughtsForUserException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(RuntimeException::class);
    }

    function it_contains_the_user(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }

    function it_contains_the_reason()
    {
        $this->getReason()->shouldReturn(self::REASON_MESSAGE);
    }

    function it_has_a_message_by_default()
    {
        $this->getMessage()->shouldReturn(
            'Could not fetch number of thoughts for user [2]: '. self::REASON_MESSAGE
        );
    }
}
