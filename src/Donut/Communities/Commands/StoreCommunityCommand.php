<?php

namespace Angelov\Donut\Communities\Commands;

use Symfony\Component\Validator\Constraints as Assert;
use Angelov\Donut\Users\User;

class StoreCommunityCommand
{
    /**
     * @Assert\NotBlank(message="Please enter a name for the community.")
     */
    private $name;
    private $author;
    private $description;
    private $id;

    public function __construct(string $id, string $name, User $author, string $description = '')
    {
        $this->name = $name;
        $this->author = $author;
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

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function getDescription() : string
    {
        return $this->description;
    }
}
