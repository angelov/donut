<?php

namespace SocNet\Communities\Commands;

use Symfony\Component\Validator\Constraints as Assert;
use SocNet\Users\User;

class StoreCommunityCommand
{
    /**
     * @Assert\NotBlank(message="Please enter a name for the community.")
     */
    private $name;
    private $author;
    private $description;

    public function __construct(string $name, User $author, string $description = '')
    {
        $this->name = $name;
        $this->author = $author;
        $this->description = $description;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function getDescription() : string
    {
        return $this->description;
    }
}
