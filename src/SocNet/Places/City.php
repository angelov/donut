<?php

namespace SocNet\Places;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SocNet\Users\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="city")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="SocNet\Users\User", mappedBy="city")
     */
    private $residents;

    public function __construct(string $id, string $name)
    {
        $this->name = $name;
        $this->residents = new ArrayCollection();
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

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * @return User[]
     */
    public function getResidents() : array
    {
        return $this->residents->getValues();
    }

    public function addResident(User $user)
    {
        if (! $this->residents->contains($user)) {
            $this->residents->add($user);
            $user->setCity($this);
        }
    }
}
