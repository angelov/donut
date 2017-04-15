<?php

namespace SocNet\Thoughts\ThoughtsCounter;

use Psr\Log\LoggerInterface;
use SocNet\Users\User;

class LoggingThoughtsCounter implements ThoughtsCounterInterface
{
    private $decorated;
    private $logger;

    public function __construct(ThoughtsCounterInterface $decorated, LoggerInterface $logger)
    {
        $this->decorated = $decorated;
        $this->logger = $logger;
    }

    public function increase(User $user): void
    {
        $this->decorated->increase($user);
    }

    public function count(User $user): int
    {
        try {
            return $this->decorated->count($user);
        } catch (\Exception $exception) {
            $this->logger->alert($exception->getMessage());

            throw $exception;
        }
    }
}
