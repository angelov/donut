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

namespace Angelov\Donut\Friendships;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Angelov\Donut\Users\User;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship")
 */
class Friendship
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", inversedBy="friendships", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", cascade={"persist"})
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id", nullable=false)
     */
    private $friend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $id, User $user, User $friend)
    {
        $this->createdAt = new DateTime();
        $this->user = $user;
        $this->friend = $friend;
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function setUser(User $user) : void
    {
        $this->user = $user;
        $user->addFriendship($this);
    }

    public function getFriend() : User
    {
        return $this->friend;
    }

    public function setFriend(User $friend) : void
    {
        $this->friend = $friend;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }
}
