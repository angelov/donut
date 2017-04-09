<?php

namespace AppBundle\ThoughtsCounter;

use SocNet\Users\User;

interface ThoughtsCounterInterface
{
    public function increase(User $user);

    public function count(User $user) : int;
}
