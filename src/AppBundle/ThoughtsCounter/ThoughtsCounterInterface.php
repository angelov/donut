<?php

namespace AppBundle\ThoughtsCounter;

use AppBundle\Entity\User;

interface ThoughtsCounterInterface
{
    public function increase(User $user);

    public function count(User $user) : int;
}
