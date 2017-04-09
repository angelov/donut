<?php

namespace SocNet\Users\Handlers;

use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\Commands\StoreUserCommand;
use SocNet\Users\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class StoreUserCommandHandler
{
    private $users;
    private $passwordEncoder;

    public function __construct(UsersRepositoryInterface $users, UserPasswordEncoder $passwordEncoder)
    {
        $this->users = $users;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(StoreUserCommand $command)
    {
        $user = new User(
            $command->getName(),
            $command->getEmail(),
            $command->getPassword()
        );

        $password = $this->passwordEncoder->encodePassword($user, $command->getPassword());

        $user->setPassword($password);

        $this->users->store($user);
    }
}
