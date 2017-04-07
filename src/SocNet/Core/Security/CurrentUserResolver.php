<?php

namespace SocNet\Core\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CurrentUserResolver implements CurrentUserResolverInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new AuthenticationException('Nobody is authenticated.');
        }

        if (!is_object($token->getUser())) {
            throw new AuthenticationException('User authenticated anonymously');
        }

        return $token->getUser();
    }
}
