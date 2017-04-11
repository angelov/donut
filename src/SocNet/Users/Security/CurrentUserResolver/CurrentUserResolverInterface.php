<?php

namespace SocNet\Users\Security\CurrentUserResolver;

use SocNet\Users\User;

interface CurrentUserResolverInterface
{
    public function getUser() : User;
}
