<?php

namespace spec\SocNet\Friendships\MutualFriendsResolver\UsersProvider;

use SocNet\Friendships\MutualFriendsResolver\UsersProvider\RepositoryUsersProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use SocNet\Users\Repositories\UsersRepositoryInterface;

class RepositoryUsersProviderSpec extends ObjectBehavior
{
    function let(UsersRepositoryInterface $users)
    {
        $this->beConstructedWith($users);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RepositoryUsersProvider::class);
    }

    function it_is_users_provider()
    {
        $this->shouldImplement(UsersProviderInterface::class);
    }

    function it_passes_get_by_id_calls_to_repository(UsersRepositoryInterface $users)
    {
        $users->find('5')->shouldBeCalled();

        $this->getById('5');
    }
}
