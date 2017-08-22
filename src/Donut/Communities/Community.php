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

namespace Angelov\Donut\Communities;

use DateTime;
use Angelov\Donut\Users\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Angelov\Donut\Communities\Exceptions\CommunityMemberNotFoundException;

/**
 * @ORM\Entity
 * @ORM\Table(name="community")
 */
class Community
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", cascade={"remove", "persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="Angelov\Donut\Users\User")
     * @ORM\JoinTable(name="community_member")
     */
    private $members;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $id, string $name, User $author, string $description = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->author = $author;
        $this->description = $description;
        $this->setCreatedAt(new DateTime());
        $this->members = new ArrayCollection();
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function setDescription(string $description = '') : void
    {
        $this->description = $description;
    }

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function setAuthor(User $author) : void
    {
        $this->author = $author;
    }

    /**
     * @return User[]
     */
    public function getMembers() : array
    {
        return $this->members->getValues();
    }

    public function addMember(User $user) : void
    {
        if ($this->hasMember($user)) {
            return;
        }

        $this->members[] = $user;
    }

    public function hasMember(User $user) : bool
    {
        return $this->members->contains($user);
    }

    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }

    public function removeMember(User $user) : void
    {
        if (!$this->hasMember($user)) {
            throw new CommunityMemberNotFoundException($user, $this);
        }

        $this->members->removeElement($user);
    }
}
