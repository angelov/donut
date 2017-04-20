<?php

namespace spec\SocNet\Friendships\MutualFriendsResolver;

use GraphAware\Neo4j\Client\Client;
use SocNet\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;
use SocNet\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;

class Neo4jMutualFriendsResolverSpec extends ObjectBehavior
{
    function let(Client $client, UsersProviderInterface $usersProvider)
    {
        $this->beConstructedWith($client, $usersProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Neo4jMutualFriendsResolver::class);
    }

    function it_is_mutual_friends_resolver()
    {
        $this->shouldImplement(MutualFriendsResolverInterface::class);
    }
}
