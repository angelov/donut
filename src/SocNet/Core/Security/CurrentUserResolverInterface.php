<?php

namespace SocNet\Core\Security;

use AppBundle\Entity\User;

interface CurrentUserResolverInterface
{
    public function getUser() : User;
}
