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

namespace Angelov\Donut\Users\Commands;

use Angelov\Donut\Users\Validation\Constraints\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class StoreUserCommand
{
    private $id;

    /**
     * @Assert\NotBlank(message="Please enter your name.")
     */
    private $name;

    /**
     * @Assert\Email()
     * @Assert\NotBlank(message="Please enter your email.")
     * @UniqueEmail(message="The email is already in use.")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Please enter your password.")
     * @Assert\Length(min="6", minMessage="The password must be at least 6 characters long.")
     */
    private $password;

    private $cityId;

    public function __construct(string $id, string $name, string $email, string $password, string $cityId)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->cityId = $cityId;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getCityId() : string
    {
        return $this->cityId;
    }
}
