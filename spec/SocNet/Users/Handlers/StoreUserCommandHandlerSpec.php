<?php

namespace spec\SocNet\Users\Handlers;

use SocNet\Users\Repositories\UsersRepositoryInterface;
use Prophecy\Argument;
use SocNet\Users\Commands\StoreUserCommand;
use SocNet\Users\Handlers\StoreUserCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class StoreUserCommandHandlerSpec extends ObjectBehavior
{
    const USER_NAME = 'John';
    const USER_EMAIL = 'john@example.com';
    const USER_PASSWORD = '123456';

    function let(UsersRepositoryInterface $repository, UserPasswordEncoder $passwordEncoder, StoreUserCommand $command)
    {
        $this->beConstructedWith($repository, $passwordEncoder);

        $command->getName()->willReturn(self::USER_NAME);
        $command->getEmail()->willReturn(self::USER_EMAIL);
        $command->getPassword()->willReturn(self::USER_PASSWORD);

        $passwordEncoder->encodePassword(Argument::type(User::class), Argument::type('string'))->willReturn('encoded');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreUserCommandHandler::class);
    }

    function it_stores_new_users(StoreUserCommand $command, UsersRepositoryInterface $repository)
    {
        $repository->store(Argument::type(User::class))->shouldBeCalled();

        $this->handle($command);
    }

    function it_encodes_the_passwords(StoreUserCommand $command, UserPasswordEncoder $passwordEncoder)
    {
        $passwordEncoder->encodePassword(Argument::type(User::class), Argument::type('string'))->shouldBeCalled();

        $this->handle($command);
    }
}
