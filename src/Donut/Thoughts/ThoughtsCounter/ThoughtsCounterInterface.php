<?php

namespace Angelov\Donut\Thoughts\ThoughtsCounter;

use Angelov\Donut\Users\User;

interface ThoughtsCounterInterface
{
    public function increase(User $user) : void;

    public function decrease(User $user) : void;

    public function count(User $user) : int;
}
