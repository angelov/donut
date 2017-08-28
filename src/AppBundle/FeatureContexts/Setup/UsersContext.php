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

namespace AppBundle\FeatureContexts\Setup;

use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Places\City;
use Angelov\Donut\Users\Commands\StoreUserCommand;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UsersContext implements Context
{
    private $em;
    private $storage;
    private $passwordEncoder;
    private $uuidGenerator;
    private $users;
    private $commandBus;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoder $passwordEncoder,
        StorageInterface $storage,
        UuidGeneratorInterface $uuidGenerator,
        UsersRepositoryInterface $users,
        CommandBusInterface $commandBus
    ) {
        $this->em = $entityManager;
        $this->storage = $storage;
        $this->passwordEncoder = $passwordEncoder;
        $this->uuidGenerator = $uuidGenerator;
        $this->users = $users;
        $this->commandBus = $commandBus;
    }

    /**
     * @Given there is a user :name with email :email and password :password
     */
    public function thereIsAUserWithEmailAndPassword($name, $email, $password) : void
    {
        $id = $this->uuidGenerator->generate();
        $city = new City($id, 'Valandovo');
        $this->em->persist($city);

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreUserCommand($id, $name, $email, $password, $city->getId()));

        $user = $this->users->find($id);

        $key = 'created_user_' . $name;
        $this->storage->set($key, $user);

        $this->storage->set('last_created_user', $user);
    }

    /**
     * @Given I am :name
     */
    public function iAm($name) : void
    {
        $this->storage->set('user_name', $name);
    }

    /**
     * @Given I am registered with email :email and password :password
     */
    public function iAmRegisteredWithEmailAndPassword($email, $password) : void
    {
        $name = $this->storage->get('user_name', 'John Smith');
        $this->thereIsAUserWithEmailAndPassword($name, $email, $password);
    }

    /**
     * @Given there are users :first, :second, :third, :fourth, :fifth and :sixth
     * @Given there are users :first, :second, :third and :fourth
     * @Given there are users :first, :second and :third
     * @Given there are users :first and :second
     * @Given there is a user :first
     */
    public function thereAreUsersWithNames(string ...$names) : void
    {
        foreach ($names as $name) {
            $email = str_replace(' ', '.', strtolower($name));
            $this->thereIsAUserWithEmailAndPassword($name, $email, '123456'); // @todo use a factory or something
        }
    }

    /**
     * @Given there is a user :name with email :email
     */
    public function thereIsAUserNameWithEmail(string $name, string $email) : void
    {
        $this->thereIsAUserWithEmailAndPassword($name, $email, '123456');
    }
}
