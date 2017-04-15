<?php

namespace SocNet\Thoughts\ThoughtsCounter;

use SocNet\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use SocNet\Users\User;

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

    public function count(User $user): int
    {
        try {
            return $this->decorated->count($user);
        } catch (CouldNotCountThoughtsForUserException $exception) {
            return 0;
        }
    }
}
