<?php

namespace SocNet\Thoughts;

use DateTime;
use SocNet\Users\User;
use JMS\Serializer\Annotation as Serializer;

class Thought
{
    /**
     * @Serializer\Type(name="string")
     */
    private $id;

    private $content;

    private $author;

    private $createdAt;

    public function __construct(string $id, User $author, string $content, \DateTime $createdAt = null)
    {
        $this->author = $author;
        $this->content = $content;
        $this->id = $id;
        $this->createdAt = $createdAt ?? new DateTime();
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function setContent(string $content) : void
    {
        $this->content = $content;
    }

    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }

    public function getAuthor() : User
    {
        return $this->author;
    }

    public function setAuthor(User $author) : void
    {
        $this->author = $author;
        $author->addThought($this);
    }
}
