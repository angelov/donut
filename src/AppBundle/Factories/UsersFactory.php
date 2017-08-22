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

namespace AppBundle\Factories;

use Faker\Generator as Faker;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Places\City;
use Angelov\Donut\Users\User;

class UsersFactory
{
    private $faker;
    private $uuidGenerator;
    private $citiesFactory;

    private $name;
    private $email;
    private $password;
    private $city;

    public function __construct(Faker $faker, UuidGeneratorInterface $uuidGenerator, CitiesFactory $citiesFactory)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;
        $this->citiesFactory = $citiesFactory;
    }

    public function withName(string $name) : UsersFactory
    {
        $this->name = $name;
        return $this;
    }

    public function withEmail(string $email) : UsersFactory
    {
        $this->email = $email;
        return $this;
    }

    public function withPassword(string $password) : UsersFactory
    {
        $this->password = $password;
        return $this;
    }

    public function fromCity(City $city) : UsersFactory
    {
        $this->city = $city;
        return $this;
    }

    public function get() : User
    {
        return new User(
            $this->uuidGenerator->generate(),
            $this->name ?? $this->faker->name,
            $this->email ?? $this->faker->safeEmail,
            $this->password ?? $this->faker->password,
            $this->city ?? $this->citiesFactory->get()
        );
    }
}
