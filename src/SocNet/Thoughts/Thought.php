<?php

namespace SocNet\Thoughts;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use SocNet\Users\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="thought")
 */
class Thought
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
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User", inversedBy="thoughts", fetch="EAGER")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $id, User $author, string $content)
    {
        $this->createdAt = new DateTime();
        $this->author = $author;
        $this->content = $content;
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
