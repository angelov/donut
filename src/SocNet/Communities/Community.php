<?php

namespace SocNet\Communities;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SocNet\Communities\Exceptions\CommunityMemberNotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="community")
 */
class Community
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Please enter a name for the community.")
     */
    private $name = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description = '';

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinTable(name="community_member")
     */
    private $members;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @todo Change the constructor so it can't be constructed without name and author
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->members = new ArrayCollection();
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description = '')
    {
        $this->description = $description;
    }

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
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

    public function addMember(User $user)
    {
        if ($this->hasMember($user)) {
            return;
        }

        $this->members[] = $user;
    }

    public function hasMember(User $user)
    {
        return $this->members->contains($user);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function removeMember(User $user)
    {
        if (!$this->hasMember($user)) {
            throw new CommunityMemberNotFoundException($user, $this);
        }

        $this->members->removeElement($user);
    }
}
