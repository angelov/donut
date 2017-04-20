<?php

namespace spec\SocNet\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;

use SocNet\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;
use SocNet\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver\IdsResolver;
use SocNet\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver\Neo4jMutualFriendsResolver;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use SocNet\Users\User;

class Neo4jMutualFriendsResolverSpec extends ObjectBehavior
{
    function let(IdsResolver $idsResolver, UsersProviderInterface $usersProvider)
    {
        $this->beConstructedWith($idsResolver, $usersProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Neo4jMutualFriendsResolver::class);
    }

    function it_is_mutual_friends_resolver()
    {
        $this->shouldImplement(MutualFriendsResolverInterface::class);
    }

    function it_returns_empty_array_if_same_user_is_submitted_twice(User $first, User $second)
    {
        $first->equals($second)->willReturn(true);

        $this->forUsers($first, $second);
    }

    function it_returns_empty_array_if_there_are_no_mutual_friends(IdsResolver $idsResolver, User $first, User $second)
    {
        $first->equals($second)->willReturn(false);
        $first->getId()->willReturn('1');
        $second->getId()->willReturn('2');

        $idsResolver->findMutualFriends('1', '2')->willReturn([]);

        $this->forUsers($first, $second)->shouldReturn([]);
    }

    function it_returns_array_of_users_if_mutual_friends_are_found(
        IdsResolver $idsResolver,
        User $first,
        User $second,
        User $mutual1,
        User $mutual2,
        UsersProviderInterface $usersProvider
    ) {
        $first->equals($second)->willReturn(false);
        $first->getId()->willReturn('1');
        $second->getId()->willReturn('2');

        $idsResolver->findMutualFriends('1', '2')->willReturn(['3', '4']);

        $usersProvider->getById('3')->willReturn($mutual1);
        $usersProvider->getById('4')->willReturn($mutual2);

        $this->forUsers($first, $second)->shouldReturn([$mutual1, $mutual2]);
    }
}
