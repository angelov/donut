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

namespace Angelov\Donut\Friendships\FriendshipRequests;

use Doctrine\ORM\Mapping as ORM;
use Angelov\Donut\Users\User;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship_request")
 */
class FriendshipRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", inversedBy="sentFriendshipRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", inversedBy="receivedFriendshipRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $toUser;

    public function __construct(string $id, User $sender, User $receiver)
    {
        $this->setFromUser($sender);
        $this->setToUser($receiver);
        $this->setId($id);
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getFromUser() : User
    {
        return $this->fromUser;
    }

    public function setFromUser(User $fromUser) : void
    {
        $this->fromUser = $fromUser;
        $fromUser->addSentFriendshipRequest($this);
    }

    public function getToUser() : User
    {
        return $this->toUser;
    }

    public function setToUser(User $toUser) : void
    {
        $this->toUser = $toUser;
        $toUser->addReceivedFriendshipRequest($this);
    }
}
