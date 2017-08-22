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
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Users\User;

class ThoughtsFactory
{
    private $faker;
    private $uuidGenerator;
    private $usersFactory;

    private $content;
    private $author;

    public function __construct(Faker $faker, UuidGeneratorInterface $uuidGenerator, UsersFactory $usersFactory)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;
        $this->usersFactory = $usersFactory;
    }

    public function sharedBy(User $user) : ThoughtsFactory
    {
        $this->author = $user;
        return $this;
    }

    public function withContent(string $content) : ThoughtsFactory
    {
        $this->content = $content;
        return $this;
    }

    public function get() : Thought
    {
        return new Thought(
            $this->uuidGenerator->generate(),
            $this->author ?? $this->usersFactory->get(),
            $this->content ?? $this->faker->sentence
        );
    }
}
