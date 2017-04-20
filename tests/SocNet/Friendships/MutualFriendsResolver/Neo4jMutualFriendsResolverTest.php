<?php

namespace SocNet\Tests\Friendships\MutualFriendsResolver;

use GraphAware\Neo4j\Client\Client;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipsRecorder\Neo4jFriendshipsRecorder;
use SocNet\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Neo4jMutualFriendsResolverTest extends KernelTestCase
{
    /** @var Client */
    private $client;

    /** @var Neo4jMutualFriendsResolver */
    private $resolver;

    /** @var UsersRepositoryInterface */
    private $users;

    /** @var Neo4jFriendshipsRecorder */
    private $recorder;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->client = $kernel->getContainer()->get('neo4j.client.default');
        $this->resolver = $kernel->getContainer()->get('app.mutual_friends_resolver.neo4j');
        $this->users = $kernel->getContainer()->get('app.users.repository.default');
        $this->recorder = $kernel->getContainer()->get('app.friendships.friendships_recorder.neo4j');
    }

    /** @test */
    public function it_returns_empty_array_for_same_user()
    {
        $user = new User('John', 'john@example.com', '123456');
        $this->users->store($user);

        $mutual = $this->resolver->forUsers($user, $user);

        $this->assertEquals([], $mutual);
    }

    /** @test */
    public function it_returns_empty_array_when_there_are_no_mutual_friends()
    {
        $user = new User('John', 'john@example.com', '123456');
        $this->users->store($user);

        $user2 = new User('James', 'james@example.com', '123456');
        $this->users->store($user2);

        $mutual = $this->resolver->forUsers($user, $user2);

        $this->assertEquals([], $mutual);
    }


    /** @test */
    public function it_returns_array_of_mutual_friends()
    {
        $user = new User('John', 'john@example.com', '123456');
        $this->users->store($user);

        $user2 = new User('James', 'james@example.com', '123456');
        $this->users->store($user2);

        $user3 = new User('Emma', 'emma@example.com', '123456');
        $this->users->store($user3);

        $user4 = new User('Bob', 'bob@example.com', '123456');
        $this->users->store($user4);

        $this->recorder->recordCreated(new Friendship($user2, $user));
        $this->recorder->recordCreated(new Friendship($user3, $user));
        $this->recorder->recordCreated(new Friendship($user2, $user4));
        $this->recorder->recordCreated(new Friendship($user3, $user4));

        $mutual = $this->resolver->forUsers($user2, $user3);

        $this->assertEquals([$user4, $user], $mutual);
    }
}
