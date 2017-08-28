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
use Angelov\Donut\Users\Commands\StoreUserCommand;
use Angelov\Donut\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use Angelov\Donut\Users\Events\UserRegisteredEvent;
use Angelov\Donut\Users\Exceptions\EmailTakenException;
use Angelov\Donut\Users\Handlers\StoreUserCommandHandler;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Donut\Places\Repositories\CitiesRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StoreUserCommandHandlerSpec extends ObjectBehavior
{
    const USER_ID = 'uuid value';
    const USER_NAME = 'John';
    const USER_EMAIL = 'john@example.com';
    const USER_PASSWORD = '123456';
    const CITY_ID = 'city id';

    function let(
        EventBusInterface $eventBus,
        UsersRepositoryInterface $users,
        CitiesRepositoryInterface $cities,
        UserPasswordEncoder $passwordEncoder,
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        StoreUserCommand $command,
        City $city
    ) {
        $this->beConstructedWith($users, $cities, $passwordEncoder, $emailAvailabilityChecker, $eventBus);

        $command->getName()->willReturn(self::USER_NAME);
        $command->getEmail()->willReturn(self::USER_EMAIL);
        $command->getPassword()->willReturn(self::USER_PASSWORD);
        $command->getCityId()->willReturn(self::CITY_ID);
        $command->getId()->willReturn(self::USER_ID);

        $emailAvailabilityChecker->isTaken(Argument::any())->willReturn(false);

        $cities->find(self::CITY_ID)->willReturn($city);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreUserCommandHandler::class);
    }

    function it_throws_exception_if_the_city_is_not_found(CitiesRepositoryInterface $cities, StoreUserCommand $command)
    {
        $cities->find(self::CITY_ID)->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_throws_exception_when_the_email_is_taken(StoreUserCommand $command, EmailAvailabilityCheckerInterface $emailAvailabilityChecker)
    {
        $emailAvailabilityChecker->isTaken(self::USER_EMAIL)->willReturn(true);

        $this->shouldThrow(EmailTakenException::class)->during('handle', [$command]);
    }

    function it_stores_new_users(
        StoreUserCommand $command,
        UsersRepositoryInterface $users,
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
        $command->getCityId()->shouldBeCalled();

        $passwordEncoder->encodePassword(Argument::type(User::class), Argument::type('string'))->shouldBeCalled();
        $users->store(Argument::type(User::class))->shouldBeCalled();

        $eventBus->fire(Argument::type(UserRegisteredEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
