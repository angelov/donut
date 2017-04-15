<?php

namespace spec\SocNet\Thoughts\ThoughtsCounter;

use Exception;
use Predis\Client;
use SocNet\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use SocNet\Thoughts\ThoughtsCounter\RedisThoughtsCounter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use SocNet\Users\User;

class RedisThoughtsCounterSpec extends ObjectBehavior
{
    function let(Client $redisClient, User $user)
    {
        $this->beConstructedWith($redisClient);

        $user->getId()->willReturn('2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RedisThoughtsCounter::class);
    }

    function it_is_thoughts_counter()
    {
        $this->shouldImplement(ThoughtsCounterInterface::class);
    }

    function it_increases_number_of_thoughts_for_user(User $user, Client $redisClient)
    {
        $redisClient->incr(Argument::type('string'))->shouldBeCalled();

        $this->increase($user);
    }

    function it_decreases_number_of_thoughts_for_user(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn(2);

        $redisClient->decr(Argument::type('string'))->shouldBeCalled();

        $this->decrease($user);
    }

    function it_does_not_decrease_bellow_zero(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn(0);

        $redisClient->decr(Argument::type('string'))->shouldNotBeCalled();

        $this->decrease($user);
    }

    function it_returns_zero_if_user_has_no_shared_thoughts(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn(null);

        $this->count($user)->shouldReturn(0);
    }

    function it_returns_number_of_thoughts_for_user(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn('2');

        $this->count($user)->shouldReturn(2);
    }

    function it_throws_exception_when_something_is_wrong_with_redis(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->willThrow(Exception::class);

        $this->shouldThrow(CouldNotCountThoughtsForUserException::class)->during('count', [$user]);
    }
}
