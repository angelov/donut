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

namespace Angelov\Donut\Users\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Commands\StoreUserCommand;
use Angelov\Donut\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use Angelov\Donut\Users\Events\UserRegisteredEvent;
use Angelov\Donut\Users\Exceptions\EmailTakenException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Donut\Places\Repositories\CitiesRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class StoreUserCommandHandler
{
    private $users;
    private $passwordEncoder;
    private $emailAvailabilityChecker;
    private $eventBus;
    private $cities;

    public function __construct(
        UsersRepositoryInterface $users,
        CitiesRepositoryInterface $cities,
        UserPasswordEncoder $passwordEncoder,
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        EventBusInterface $eventBus
    ) {
        $this->users = $users;
        $this->passwordEncoder = $passwordEncoder;
        $this->emailAvailabilityChecker = $emailAvailabilityChecker;
        $this->eventBus = $eventBus;
        $this->cities = $cities;
    }

    /**
     * @throws EmailTakenException
     * @throws ResourceNotFoundException
     */
    public function handle(StoreUserCommand $command) : void
    {
        $this->assertEmailNotTaken($command->getEmail());

        $city = $this->cities->find($command->getCityId());

        $user = new User(
            $command->getId(),
            $command->getName(),
            $command->getEmail(),
            $command->getPassword(),
            $city
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
