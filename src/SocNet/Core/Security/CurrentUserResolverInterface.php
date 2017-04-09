<?php

namespace SocNet\Core\Security;

use SocNet\Users\User;

interface CurrentUserResolverInterface
{
    public function getUser() : User;
}
