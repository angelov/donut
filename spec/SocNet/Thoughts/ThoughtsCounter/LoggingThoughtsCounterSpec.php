<?php

namespace spec\SocNet\Thoughts\ThoughtsCounter;

use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use SocNet\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use SocNet\Thoughts\ThoughtsCounter\LoggingThoughtsCounter;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use SocNet\Users\User;

class LoggingThoughtsCounterSpec extends ObjectBehavior
{
    function let(ThoughtsCounterInterface $inner, LoggerInterface $logger)
    {
        $this->beConstructedWith($inner, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LoggingThoughtsCounter::class);
    }

    function it_is_thoughts_counter()
    {
        $this->shouldImplement(ThoughtsCounterInterface::class);
    }

    function it_passes_increase_calls_to_decorated_counter(ThoughtsCounterInterface $inner, User $user)
    {
        $inner->increase($user)->shouldBeCalled();

        $this->increase($user);
    }

    function it_passes_decrease_calls_to_decorated_counter(ThoughtsCounterInterface $inner, User $user)
    {
        $inner->decrease($user)->shouldBeCalled();

        $this->decrease($user);
    }

    function it_logs_exceptions_from_decorated_counter_when_counting(ThoughtsCounterInterface $inner, User $user, LoggerInterface $logger)
    {
        $inner->count($user)->willThrow(CouldNotCountThoughtsForUserException::class);

        $logger->alert(Argument::type('string'))->shouldBeCalled();

        $this->shouldThrow(CouldNotCountThoughtsForUserException::class)->during('count', [$user]);
    }
}
