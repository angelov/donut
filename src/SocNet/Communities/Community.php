<?php

namespace SocNet\Communities;

use DateTime;
use SocNet\Users\User;
use Doctrine\Common\Collections\ArrayCollection;
use SocNet\Communities\Exceptions\CommunityMemberNotFoundException;

class Community
{
    private $id;

    private $name;

    private $description;

    private $author;

    private $members;

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
