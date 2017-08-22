<?php

namespace Angelov\Donut\Thoughts\ThoughtsCounter;

use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use Angelov\Donut\Users\User;

class EnsuringDefaultValueThoughtCounter implements ThoughtsCounterInterface
{
    private $decorated;

    public function __construct(ThoughtsCounterInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function increase(User $user): void
    {
        $this->decorated->increase($user);
    }

    public function decrease(User $user): void
    {
        $this->decorated->decrease($user);
    }

    public function count(User $user): int
    {
        try {
            return $this->decorated->count($user);
        } catch (CouldNotCountThoughtsForUserException $exception) {
            return 0;
        }
    }
}
