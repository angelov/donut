<?php

namespace spec\SocNet\Users\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Core\Exceptions\ResourceNotFoundException;
use SocNet\Places\City;
use SocNet\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use SocNet\Users\Events\UserRegisteredEvent;
use SocNet\Users\Exceptions\EmailTakenException;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use Prophecy\Argument;
use SocNet\Users\Commands\StoreUserCommand;
use SocNet\Users\Handlers\StoreUserCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StoreUserCommandHandlerSpec extends ObjectBehavior
{
    const USER_NAME = 'John';
    const USER_EMAIL = 'john@example.com';
    const USER_PASSWORD = '123456';

    function let(
        EventBusInterface $eventBus,
        UsersRepositoryInterface $repository,
        UserPasswordEncoder $passwordEncoder,
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        StoreUserCommand $command,
        City $city
    ) {
        $this->beConstructedWith($repository, $passwordEncoder, $emailAvailabilityChecker, $eventBus);

        $command->getName()->willReturn(self::USER_NAME);
        $command->getEmail()->willReturn(self::USER_EMAIL);
        $command->getPassword()->willReturn(self::USER_PASSWORD);
        $command->getCity()->willReturn($city);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreUserCommandHandler::class);
    }

    function it_stores_new_users(
        StoreUserCommand $command,
        UsersRepositoryInterface $repository,
        UserPasswordEncoderInterface $passwordEncoder,
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        EventBusInterface $eventBus
    ) {
        $emailAvailabilityChecker->isTaken(self::USER_EMAIL)->willReturn(false);
        $passwordEncoder->encodePassword(Argument::type(User::class), Argument::type('string'))->willReturn('encoded');

        $command->getName()->shouldBeCalled();
        $command->getPassword()->shouldBeCalled();
        $command->getEmail()->shouldBeCalled();
        $command->getCity()->shouldBeCalled();

        $passwordEncoder->encodePassword(Argument::type(User::class), Argument::type('string'))->shouldBeCalled();
        $repository->store(Argument::type(User::class))->shouldBeCalled();

        $eventBus->fire(Argument::type(UserRegisteredEvent::class))->shouldBeCalled();

        $this->handle($command);
    }

    function it_throws_exception_when_the_email_is_taken(StoreUserCommand $command, EmailAvailabilityCheckerInterface $emailAvailabilityChecker)
    {
        $emailAvailabilityChecker->isTaken(self::USER_EMAIL)->willReturn(true);

        $this->shouldThrow(EmailTakenException::class)->during('handle', [$command]);
    }
}
