<?php

namespace Angelov\Donut\Users\Security\Provider;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RepositoryUserProvider implements UserProviderInterface
{
    private $users;

    public function __construct(UsersRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function loadUserByUsername($email) : UserInterface
    {
        try {
            return $this->users->findByEmail($email);
        } catch (ResourceNotFoundException $exception) {
            throw new UsernameNotFoundException(sprintf(
                'Could not find a user with email [%s]',
                $email
            ));
        }
    }

    public function refreshUser(UserInterface $user) : UserInterface
    {
        return $this->users->findByEmail(
            $user->getUsername()
        );
    }

    public function supportsClass($class) : bool
    {
        return $class === User::class;
    }
}
