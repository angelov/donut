<?php

namespace SocNet\Tests\Thoughts\ThoughtsCounter;

use Predis\Client;
use SocNet\Thoughts\ThoughtsCounter\RedisThoughtsCounter;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RedisThoughtsCounterTest extends KernelTestCase
{
    /** @var Client */
    private $redisClient;

    /** @var RedisThoughtsCounter */
    private $counter;

    /** @var UsersRepositoryInterface */
    private $usersRepository;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->redisClient = $kernel->getContainer()->get('snc_redis.default');
        $this->counter = $kernel->getContainer()->get('app.thoughts.thoughts_counter.redis');
        $this->usersRepository = $kernel->getContainer()->get('app.users.repository.default');
    }

    /** @test */
    public function it_increases_number_of_thoughts_for_first_time_publishers()
    {
        $user = new User('John', 'john@example.com', '123456');
        $this->usersRepository->store($user);

        $this->counter->increase($user);

        $count = $this->redisClient->get('user_thoughts_' . $user->getId());

        $this->assertEquals(1, $count);
    }

    public function it_increases_number_of_thoughts_for_existing_users()
    {
        $user = new User('John', 'john@example.com', '123456');
        $this->usersRepository->store($user);

        $this->counter->increase($user);
        $this->counter->increase($user);

        $count = $this->redisClient->get('user_thoughts_' . $user->getId());

        $this->assertEquals(2, $count);
    }

    public function it_fetches_number_of_thougts_for_user()
    {
        $user = new User('John', 'john@example.com', '123456');
        $this->usersRepository->store($user);

        $this->counter->increase($user);
        $this->counter->increase($user);

        $count = $this->counter->count($user);

        $this->assertEquals(2, $count);
    }
}
