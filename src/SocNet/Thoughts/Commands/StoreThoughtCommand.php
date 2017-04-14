<?php

namespace SocNet\Thoughts\Commands;

use SocNet\Users\User;
use Symfony\Component\Validator\Constraints as Assert;

class StoreThoughtCommand
{
    private $author;

    /**
     * @Assert\NotBlank(message="Please write the content of your thought.")
     * @Assert\Length(min=1, max="140", maxMessage="Thoughts can't be longer than 140 characters.")
     */
    private $content;

    public function __construct(User $author, string $content)
    {
        $this->author = $author;
        $this->content = $content;
    }

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function getContent() : string
    {
        return $this->content;
    }
}