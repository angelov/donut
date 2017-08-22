<?php

namespace Angelov\Donut\Thoughts\ThoughtsCounter;

use Predis\Client;
use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use Angelov\Donut\Users\User;

class RedisThoughtsCounter implements ThoughtsCounterInterface
{
    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function increase(User $user) : void
    {
        $this->redisClient->incr($this->resolveKey($user));
    }

    public function decrease(User $user): void
    {
        $key = $this->resolveKey($user);

        if ($this->count($user) !== 0) {
            $this->redisClient->decr($key);
        }
    }

    public function count(User $user): int
    {
        try {
            $count = $this->redisClient->get($this->resolveKey($user));
        } catch (\Exception $exception) {
            throw new CouldNotCountThoughtsForUserException($user, $exception->getMessage());
        }

        return $count ? (int) $count : 0;
    }

    private function resolveKey(User $user) : string
    {
        return sprintf('user_thoughts_%s', $user->getId());
    }
}
