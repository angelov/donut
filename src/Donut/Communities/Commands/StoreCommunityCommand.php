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

namespace Angelov\Donut\Communities\Commands;

use Symfony\Component\Validator\Constraints as Assert;

class StoreCommunityCommand
{
    /**
     * @Assert\NotBlank(message="Please enter a name for the community.")
     */
    private $name;
    private $author;
    private $description;
    private $id;

    public function __construct(string $id, string $name, string $authorId, string $description = '')
    {
        $this->name = $name;
        $this->author = $authorId;
        $this->description = $description;
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

    public function getAuthorId() : string
    {
        return $this->author;
    }

    public function getDescription() : string
    {
        return $this->description;
    }
}
