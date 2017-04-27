<?php

namespace SocNet\Users\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use SocNet\Users\Events\UserRegisteredEvent;
use SocNet\Users\Exceptions\EmailTakenException;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\Commands\StoreUserCommand;
use SocNet\Users\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class StoreUserCommandHandler
{
    private $users;
    private $passwordEncoder;
    private $emailAvailabilityChecker;
    private $eventBus;

    public function __construct(
        UsersRepositoryInterface $users,
        UserPasswordEncoder $passwordEncoder,
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        EventBusInterface $eventBus
    ) {
        $this->users = $users;
        $this->passwordEncoder = $passwordEncoder;
        $this->emailAvailabilityChecker = $emailAvailabilityChecker;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws EmailTakenException
     */
    public function handle(StoreUserCommand $command) : void
    {
        $this->assertEmailNotTaken($command->getEmail());

        $user = new User(
            $command->getName(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getCity()
        );

        $password = $this->passwordEncoder->encodePassword($user, $command->getPassword());

        $user->setPassword($password);

        $this->users->store($user);

        $this->eventBus->fire(new UserRegisteredEvent($user));
    }

    private function assertEmailNotTaken(string $email) : void
    {
        if ($this->emailAvailabilityChecker->isTaken($email)) {
            throw new EmailTakenException($email);
        }
    }
}
