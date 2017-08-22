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

namespace Angelov\Donut\Thoughts\Commands;

use DateTime;
use Angelov\Donut\Users\User;
use Symfony\Component\Validator\Constraints as Assert;

class StoreThoughtCommand
{
    private $id;
    private $author;

    /**
     * @Assert\NotBlank(message="Please write the content of your thought.")
     * @Assert\Length(min=1, max="140", maxMessage="Thoughts can't be longer than 140 characters.")
     */
    private $content;
    private $createdAt;

    public function __construct(string $id, User $author, string $content, DateTime $createdAt = null)
    {
        $this->author = $author;
        $this->content = $content;
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function getCreatedAt() : ?DateTime
    {
        return $this->createdAt;
    }
}
