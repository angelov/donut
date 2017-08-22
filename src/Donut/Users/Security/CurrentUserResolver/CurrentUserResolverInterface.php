<?php

namespace Angelov\Donut\Users\Security\CurrentUserResolver;

use Angelov\Donut\Users\User;

interface CurrentUserResolverInterface
{
    public function getUser() : User;
}
