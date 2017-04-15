<?php

namespace SocNet\Thoughts\ThoughtsCounter;

use SocNet\Users\User;

interface ThoughtsCounterInterface
{
    public function increase(User $user) : void;

    public function decrease(User $user) : void;

    public function count(User $user) : int;
}
