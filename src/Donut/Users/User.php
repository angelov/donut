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

namespace Angelov\Donut\Users;

use JMS\Serializer\Annotation as Serializer;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Places\City;
use Angelov\Donut\Thoughts\Thought;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="The email is already in use.")
 * @Serializer\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Angelov\Donut\Thoughts\Thought", mappedBy="author")
     */
    private $thoughts;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Serializer\Expose()
     */
    private $isAdmin = false;

    /**
     * @ORM\OneToMany(targetEntity="Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest", mappedBy="fromUser")
     */
    private $sentFriendshipRequests;

    /**
     * @ORM\OneToMany(targetEntity="Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest", mappedBy="toUser")
     */
    private $receivedFriendshipRequests;

    /**
     * @ORM\OneToMany(targetEntity="Angelov\Donut\Friendships\Friendship", mappedBy="user", cascade={"remove"})
     */
    private $friendships;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Places\City", inversedBy="residents", cascade={"persist"})
     */
    private $city;

    public function __construct(string $id, string $name, string $email, string $password, City $city)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->thoughts = new ArrayCollection();
        $this->sentFriendshipRequests = new ArrayCollection();
        $this->receivedFriendshipRequests = new ArrayCollection();
        $this->friendships = new ArrayCollection();
        $this->city = $city;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getUsername() : string
    {
        return $this->email;
    }

    public function setEmail(string $email = '') : void
    {
        $this->email = $email;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getRoles() : array
    {
        return ['ROLE_USER'];
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function setPassword(string $password = '') : void
    {
        $this->password = $password;
    }

    public function getSalt() : string
    {
        return '';
    }

    /**
     * @return Thought[]
     */
    public function getThoughts() : array
    {
        return $this->thoughts->getValues();
    }

    public function addThought(Thought $thought) : void
    {
        $this->thoughts->add($thought);
    }

    public function eraseCredentials() : void
    {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name = '') : void
    {
        $this->name = $name;
    }

    public function isIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    // @todo remove admin stuff
    public function setIsAdmin(bool $isAdmin) : void
    {
        $this->isAdmin = $isAdmin;
    }

    public function equals(User $user) : bool
    {
        return $this->getId() === $user->getId();
    }

    /**
     * @return FriendshipRequest[]
     */
    public function getSentFriendshipRequests() : array
    {
        return $this->sentFriendshipRequests->getValues();
    }

    public function addSentFriendshipRequest(FriendshipRequest $friendshipRequest) : void
    {
        if (!$this->sentFriendshipRequests->contains($friendshipRequest)) {
            $this->sentFriendshipRequests->add($friendshipRequest);
        }
    }

    public function hasSentFriendshipRequestTo(User $user) : bool
    {
        /** @var FriendshipRequest $friendshipRequest */
        foreach ($this->sentFriendshipRequests as $friendshipRequest) {
            if ($friendshipRequest->getToUser()->equals($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return FriendshipRequest[]
     */
    public function getReceivedFriendshipRequests() : array
    {
        return $this->receivedFriendshipRequests->getValues();
    }

    public function addReceivedFriendshipRequest(FriendshipRequest $friendshipRequest) : void
    {
        if (!$this->receivedFriendshipRequests->contains($friendshipRequest)) {
            $this->receivedFriendshipRequests->add($friendshipRequest);
        }
    }

    public function hasReceivedFriendshipRequestFrom(User $user) : bool
    {
        foreach ($this->getReceivedFriendshipRequests() as $friendshipRequest) {
            if ($friendshipRequest->getFromUser()->equals($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return User[]
     */
    public function getFriends() : array
    {
        $friends = [];

        /** @var Friendship $friendship */
        foreach ($this->friendships as $friendship) {
            $friends[] = $friendship->getFriend();
        }

        return $friends;
    }

    public function addFriendship(Friendship $friendship) : void
    {
        $this->friendships->add($friendship);
    }

    // @todo this will have a big effect on performance, use redis or something
    public function isFriendWith(User $user) : bool
    {
        /** @var Friendship $friendship */
        foreach ($this->friendships as $friendship) {
            $friend = $friendship->getFriend();

            if ($friend->equals($user)) {
                return true;
            }
        }

        return false;
    }

    public function getCity() : City
    {
        return $this->city;
    }

    public function setCity(City $city) : void
    {
        $this->city = $city;
    }
}
