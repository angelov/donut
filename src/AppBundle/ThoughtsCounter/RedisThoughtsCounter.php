<?php

namespace AppBundle\ThoughtsCounter;

use AppBundle\Entity\User;
use Predis\Client;
use Predis\Connection\ConnectionException;
use Predis\PredisException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class RedisThoughtsCounter implements ThoughtsCounterInterface
{
    private $redisClient;
    private $logger;

    public function __construct(Client $redisClient, LoggerInterface $logger = null)
    {
        $this->redisClient = $redisClient;
        $this->logger = $logger ?: new NullLogger();
    }

    public function increase(User $user)
    {
        $this->redisClient->incr($this->resolveKey($user));
    }

    public function count(User $user): int
    {
        try {
            $value = $this->redisClient->get($this->resolveKey($user));
        } catch (ConnectionException $exception) {
            $this->logException($exception, $user);

            return 0;
        }

        return $value ? (int) $value : 0;
    }

    private function resolveKey(User $user)
    {
        return sprintf('user_comments_%s', $user->getId());
    }

    private function logException(PredisException $exception, User $user)
    {
        $this->logger->alert(sprintf(
            'Could not fetch number of thoughts for user [%d]: "%s"',
            $user->getId(),
            $exception->getMessage()
        ));
    }
}
