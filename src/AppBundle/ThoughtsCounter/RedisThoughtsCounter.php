<?php

namespace AppBundle\ThoughtsCounter;

use AppBundle\Entity\User;
use Predis\Client;

class RedisThoughtsCounter implements ThoughtsCounterInterface
{
    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function increase(User $user)
    {
        $this->redisClient->incr($this->resolveKey($user));
    }

    public function count(User $user): int
    {
        $value = $this->redisClient->get($this->resolveKey($user));

        return $value ? (int) $value : 0;
    }

    private function resolveKey(User $user)
    {
        return sprintf('user_comments_%s', $user->getId());
    }
}
