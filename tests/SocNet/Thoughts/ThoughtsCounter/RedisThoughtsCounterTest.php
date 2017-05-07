<?php

namespace SocNet\Tests\Thoughts\ThoughtsCounter;

use AppBundle\Factories\ThoughtsFactory;
use AppBundle\Factories\UsersFactory;
use Predis\Client;
use SocNet\Thoughts\ThoughtsCounter\RedisThoughtsCounter;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RedisThoughtsCounterTest extends KernelTestCase
{
    /** @var Client */
    private $redisClient;

    /** @var RedisThoughtsCounter */
    private $counter;

    /** @var UsersFactory */
    private $usersFactory;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->redisClient = $kernel->getContainer()->get('snc_redis.default');
        $this->counter = $kernel->getContainer()->get('app.thoughts.thoughts_counter.redis');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
    }

    /** @test */
    public function it_increases_number_of_thoughts_for_first_time_publishers()
    {
        $user = $this->usersFactory->get();

        $this->counter->increase($user);

        $count = $this->redisClient->get('user_thoughts_' . $user->getId());

        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_increases_number_of_thoughts_for_existing_users()
    {
        $user = $this->usersFactory->get();

        $this->counter->increase($user);
        $this->counter->increase($user);

        $count = $this->redisClient->get('user_thoughts_' . $user->getId());

        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_decreases_number_of_thougts_for_users()
    {
        $user = $this->usersFactory->get();

        $this->redisClient->set('user_thoughts_'. $user->getId(), 3);

        $this->counter->decrease($user);

        $count = $this->counter->count($user);

        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_fetches_number_of_thougts_for_user()
    {
        $user = $this->usersFactory->get();

        $this->counter->increase($user);
        $this->counter->increase($user);

        $count = $this->counter->count($user);

        $this->assertEquals(2, $count);
    }
}
