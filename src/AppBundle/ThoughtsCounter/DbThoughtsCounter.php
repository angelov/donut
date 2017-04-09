<?php

namespace AppBundle\ThoughtsCounter;

use SocNet\Users\User;

class DbThoughtsCounter implements ThoughtsCounterInterface
{
    public function increase(User $user)
    {
        // nothing to be done in this implementation
    }

    public function count(User $user): int
    {
        return count($user->getThoughts());
    }
}
