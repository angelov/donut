<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace spec\Angelov\Donut\Users\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Places\City;
use Angelov\Donut\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use Angelov\Donut\Users\Events\UserRegisteredEvent;
use Angelov\Donut\Users\Exceptions\EmailTakenException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Prophecy\Argument;
use Angelov\Donut\Users\Commands\StoreUserCommand;
use Angelov\Donut\Users\Handlers\StoreUserCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StoreUserCommandHandlerSpec extends ObjectBehavior
{
    const USER_ID = 'uuid value';
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
        $command->getId()->willReturn(self::USER_ID);
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

        $command->getId()->shouldBeCalled();
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
