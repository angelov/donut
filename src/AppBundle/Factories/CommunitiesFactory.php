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
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Users\User;

class CommunitiesFactory
{
    private $faker;
    private $uuidGenerator;
    private $usersFactory;

    private $name;
    private $description;
    private $author;

    public function __construct(Faker $faker, UuidGeneratorInterface $uuidGenerator, UsersFactory $usersFactory)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;
        $this->usersFactory = $usersFactory;
    }

    public function withName(string $name) : CommunitiesFactory
    {
        $this->name = $name;
        return $this;
    }

    public function withDescription(string $description) : CommunitiesFactory
    {
        $this->description = $description;
        return $this;
    }

    public function createdBy(User $author) : CommunitiesFactory
    {
        $this->author = $author;
        return $this;
    }

    public function get() : Community
    {
        return new Community(
            $this->uuidGenerator->generate(),
            $this->name ?? $this->faker->name,
            $this->author ?? $this->usersFactory->get(),
            $this->description ?? $this->faker->sentence
        );
    }
}
